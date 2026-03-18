<?php

namespace App\Services\Invoice;

use App\Models\Authorization\Company;
use App\Models\Authorization\User;
use App\Models\Invoice\Quotation;
use App\Models\Product\Product;
use App\Services\Authorization\DataVisibilityService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class QuotationService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
    ) {
    }

    // This prepares the quotation page with visible products and allowed companies.
    public function buildGenerateQuotePageData(?User $user, ?int $prefilledProductId = null): array
    {
        try {
            // Step 1: load the visible products and attach the live price snapshot used by the quotation form.
            $products = $this->dataVisibilityService->visibleProductQuery($user)
                ->orderBy('products.name')
                ->get()
                ->map(function ($product) use ($user) {
                    $price = $this->dataVisibilityService->resolvePrice((int) $product->id, $user);

                    $product->visible_price = $price['amount'] ?? null;
                    $product->visible_currency = $price['currency'] ?? 'INR';
                    $product->visible_price_type = $price['price_type'] ?? null;
                    $product->gst_rate = $price['gst_rate'] ?? 0;
                    $product->tax_amount = $price['tax_amount'] ?? 0;
                    $product->price_after_gst = $price['price_after_gst'] ?? null;
                    $product->min_order_quantity = $price['min_order_quantity'] ?? 1;
                    $product->max_order_quantity = $price['max_order_quantity'] ?? null;
                    $product->lot_size = $price['lot_size'] ?? 1;

                    return $product;
                });

            // Step 2: keep the page usable even when product pricing master data is still being completed.
            if ($products->isEmpty()) {
                $products = $this->loadFallbackQuotationProducts();
            }

            // Step 3: load the allowed client companies only for B2B accounts that can quote for assigned customers.
            $clientCompanies = collect();

            if ($user && $user->isB2b()) {
                $companyIds = $this->dataVisibilityService->assignedClientCompanyIds($user);

                if ($user->company_id) {
                    $companyIds[] = $user->company_id;
                }

                $clientCompanies = Company::query()
                    ->whereIn('id', array_unique($companyIds))
                    ->orderBy('name')
                    ->get();
            }

            // Step 4: return the page data in a simple shape for the quotation view.
            return [
                'products' => $products,
                'clientCompanies' => $clientCompanies,
                'prefilledProductId' => $prefilledProductId,
                'quotationFlowMode' => 'quote',
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build quotation page data.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This protects the quotation flow from saving recipient scopes the current shopper should not use.
    public function guardRecipientScope(array $validated, ?User $user): void
    {
        try {
            // Step 1: stop restricted accounts from creating customer-facing quotes for another recipient.
            if ($validated['purpose'] === 'other' && ! $this->dataVisibilityService->canGeneratePiForOther($user)) {
                throw ValidationException::withMessages([
                    'purpose' => 'You are not allowed to generate a quotation for another customer.',
                ]);
            }

            // Step 2: keep B2C accounts limited to self-quotation flow.
            if ($user && $user->isB2c() && $validated['purpose'] !== 'self') {
                throw ValidationException::withMessages([
                    'purpose' => 'B2C users can only generate quotation for self.',
                ]);
            }

            // Step 3: when a B2B account selects another company, make sure that company is inside its business visibility scope.
            if ($user && $user->isB2b() && $validated['purpose'] === 'other') {
                $targetCompanyId = isset($validated['target_company_id']) ? (int) $validated['target_company_id'] : null;

                if ($targetCompanyId && ! $this->dataVisibilityService->canAccessCompanyData($user, $targetCompanyId)) {
                    throw ValidationException::withMessages([
                        'target_company_id' => 'You can only generate quotation for your own company or assigned clients.',
                    ]);
                }
            }
        } catch (Throwable $exception) {
            Log::error('Failed to guard quotation recipient scope.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This prepares all quotation rows and keeps quantity plus pricing rules aligned with the product master.
    public function prepareQuotationItems(array $validated, ?User $user): array
    {
        try {
            // Step 1: read every submitted product and quantity row from the quotation form.
            $productIds = is_array($validated['product_id'] ?? null) ? $validated['product_id'] : [];
            $quantities = is_array($validated['quantity'] ?? null) ? $validated['quantity'] : [];
            $rowCount = max(count($productIds), count($quantities));
            $preparedItems = [];

            // Step 2: build each saved item one row at a time so error messages stay easy to understand.
            for ($index = 0; $index < $rowCount; $index++) {
                $productId = (int) ($productIds[$index] ?? 0);
                $quantity = (int) ($quantities[$index] ?? 0);

                if ($productId === 0 && $quantity === 0) {
                    continue;
                }

                $visibleProduct = $this->findVisibleProduct($user, $productId);

                if (! $visibleProduct) {
                    throw ValidationException::withMessages([
                        "product_id.$index" => 'The selected product is outside your visibility scope.',
                    ]);
                }

                $price = $this->dataVisibilityService->resolvePrice($productId, $user, $quantity);

                if (! $price) {
                    throw ValidationException::withMessages([
                        "product_id.$index" => 'No visible price is configured for the selected product.',
                    ]);
                }

                $this->validateQuantityRules($quantity, $price, $index);
                $preparedItems[] = $this->buildPreparedItem($visibleProduct, $price, $quantity);
            }

            // Step 3: stop the business flow when no valid item row was submitted.
            if ($preparedItems === []) {
                throw ValidationException::withMessages([
                    'product_id' => 'Add at least one quotation item.',
                ]);
            }

            return $preparedItems;
        } catch (Throwable $exception) {
            Log::error('Failed to prepare quotation items.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This calculates the saved quotation totals from the prepared item rows.
    public function calculateQuotationTotals(array $preparedItems): array
    {
        try {
            // Step 1: combine every line total so the saved quotation matches the visible pricing snapshot.
            return [
                'currency' => (string) ($preparedItems[0]['currency'] ?? 'INR'),
                'subtotal' => round(collect($preparedItems)->sum('line_subtotal'), 2),
                'tax_amount' => round(collect($preparedItems)->sum('line_tax_amount'), 2),
                'discount_amount' => round(collect($preparedItems)->sum('line_discount_amount'), 2),
                'price_after_gst' => round(collect($preparedItems)->sum('line_price_after_gst'), 2),
                'total_amount' => round(collect($preparedItems)->sum('line_total'), 2),
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to calculate quotation totals.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This saves the quotation header and item rows inside one transaction so business data stays consistent.
    public function createQuotationWithItems(
        array $validated,
        ?User $user,
        array $preparedItems,
        array $quotationTotals,
        string $quotationNumber,
        string $guestSessionId,
    ): Quotation {
        try {
            return DB::transaction(function () use ($validated, $user, $preparedItems, $quotationTotals, $quotationNumber, $guestSessionId): Quotation {
                // Step 1: save the quotation header so the business team has the full recipient and pricing snapshot.
                $quotation = Quotation::query()->create([
                    'quotation_number' => $quotationNumber,
                    'requester_type' => $user ? 'user' : 'guest',
                    'created_by_user_id' => $user?->id,
                    'owner_user_id' => $user?->id,
                    'owner_company_id' => $user?->company_id,
                    'target_type' => $validated['purpose'],
                    'target_name' => $validated['customer_name'],
                    'target_email' => $validated['customer_email'],
                    'target_phone' => $validated['customer_phone'] ?: null,
                    'target_company_id' => isset($validated['target_company_id']) ? (int) $validated['target_company_id'] : null,
                    'status' => 'generated',
                    'currency' => $quotationTotals['currency'],
                    'subtotal' => $quotationTotals['subtotal'],
                    'tax_amount' => $quotationTotals['tax_amount'],
                    'discount_amount' => $quotationTotals['discount_amount'],
                    'price_after_gst' => $quotationTotals['price_after_gst'],
                    'total_amount' => $quotationTotals['total_amount'],
                    'guest_session_id' => $user ? null : $guestSessionId,
                    'notes' => $validated['notes'] ?: null,
                ]);

                // Step 2: save the line items so the downloaded PDF shows the exact approved quote snapshot.
                foreach ($preparedItems as $preparedItem) {
                    $quotation->items()->create($preparedItem);
                }

                // Step 3: reload the quotation with the business relations needed by the PDF template.
                return $this->loadQuotationForPdf($quotation->id);
            });
        } catch (Throwable $exception) {
            Log::error('Failed to save quotation.', ['quotation_number' => $quotationNumber, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This renders the saved quotation into a downloadable PDF for the customer.
    public function downloadQuotationPdf(Quotation $quotation): Response
    {
        try {
            // Step 1: render the business quotation document from the dedicated PDF blade.
            $pdf = Pdf::loadView('quotation.quotation-pdf', [
                'quotation' => $quotation,
            ])->setPaper(
                config('invoice.pdf.paper', 'a4'),
                config('invoice.pdf.orientation', 'portrait'),
            );

            // Step 2: return the quotation as a direct download so the user can keep it immediately.
            return $pdf->download($quotation->quotation_number.'.pdf');
        } catch (Throwable $exception) {
            Log::error('Failed to build quotation PDF.', ['quotation_id' => $quotation->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads one visible product only when the shopper is allowed to quote on it.
    protected function findVisibleProduct(?User $user, int $productId): ?object
    {
        try {
            return $this->dataVisibilityService->visibleProductQuery($user)
                ->where('products.id', $productId)
                ->first();
        } catch (Throwable $exception) {
            Log::error('Failed to load visible quotation product.', ['product_id' => $productId, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This keeps the quotation page searchable even before the pricing master is fully configured.
    protected function loadFallbackQuotationProducts()
    {
        try {
            // Step 1: load active products with their first active sellable variant so the user can still identify catalogue items.
            return Product::query()
                ->with([
                    'variants' => fn ($builder) => $builder->where('is_active', true)->orderBy('id'),
                ])
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
                ->map(function (Product $product) {
                    // Step 2: use the first active variant as the operational source for SKU and quantity rules.
                    $primaryVariant = $product->variants->first();

                    $product->sku = $product->sku ?: ($primaryVariant?->sku ?? '');
                    $product->visible_price = 0.0;
                    $product->visible_currency = 'INR';
                    $product->visible_price_type = 'manual_review';
                    $product->gst_rate = 18.0;
                    $product->tax_amount = 0.0;
                    $product->price_after_gst = 0.0;
                    $product->min_order_quantity = max(1, (int) ($primaryVariant?->min_order_quantity ?? 1));
                    $product->max_order_quantity = $primaryVariant?->max_order_quantity;
                    $product->lot_size = max(1, (int) ($primaryVariant?->lot_size ?? 1));

                    return $product;
                })
                ->values();
        } catch (Throwable $exception) {
            Log::error('Failed to load fallback quotation products.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This builds one saved quotation item using the visible pricing snapshot.
    protected function buildPreparedItem(object $visibleProduct, array $price, int $quantity): array
    {
        try {
            // Step 1: calculate line-level values so the saved quotation always matches the visible unit pricing.
            $unitPrice = (float) ($price['amount'] ?? 0);
            $baseAmount = (float) ($price['base_amount'] ?? $unitPrice);
            $unitTaxAmount = (float) ($price['tax_amount'] ?? 0);
            $unitPriceAfterGst = (float) ($price['price_after_gst'] ?? 0);
            $unitDiscountAmount = round((float) ($price['discount_amount'] ?? 0), 2);
            $discountPercent = $baseAmount > 0 ? round(($unitDiscountAmount / $baseAmount) * 100, 2) : 0.00;
            $lineSubtotal = round($unitPrice * $quantity, 2);
            $lineTaxAmount = round($unitTaxAmount * $quantity, 2);
            $linePriceAfterGst = round($unitPriceAfterGst * $quantity, 2);
            $lineDiscountAmount = round($unitDiscountAmount * $quantity, 2);
            $lineTotal = $linePriceAfterGst;

            // Step 2: return the stored line payload in a shape that is easy to render later in the PDF.
            return [
                'product_id' => (int) $visibleProduct->id,
                'product_variant_id' => $price['product_variant_id'] ?? null,
                'product_name' => (string) $visibleProduct->name,
                'sku' => (string) ($price['variant_sku'] ?? $visibleProduct->sku),
                'variant_name' => $price['variant_name'] ?? null,
                'price_type' => $price['price_type'] ?? null,
                'currency' => $price['currency'] ?? 'INR',
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'gst_rate' => (float) ($price['gst_rate'] ?? 0),
                'unit_tax_amount' => $unitTaxAmount,
                'unit_price_after_gst' => $unitPriceAfterGst,
                'discount_percent' => $discountPercent,
                'unit_discount_amount' => $unitDiscountAmount,
                'line_subtotal' => $lineSubtotal,
                'line_tax_amount' => $lineTaxAmount,
                'line_price_after_gst' => $linePriceAfterGst,
                'line_discount_amount' => $lineDiscountAmount,
                'line_total' => $lineTotal,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build quotation item payload.', ['product_id' => $visibleProduct->id ?? null, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This applies min quantity, max quantity, and lot size before the quotation is saved.
    protected function validateQuantityRules(int $quantity, array $price, int $index): void
    {
        try {
            // Step 1: read the resolved quantity rules from the selected sellable variant.
            $minOrderQuantity = max(1, (int) ($price['min_order_quantity'] ?? 1));
            $maxOrderQuantity = $price['max_order_quantity'] ?? null;
            $lotSize = max(1, (int) ($price['lot_size'] ?? 1));

            // Step 2: enforce the minimum quantity rule.
            if ($quantity < $minOrderQuantity) {
                throw ValidationException::withMessages([
                    "quantity.$index" => "Quantity for item ".($index + 1)." must be at least {$minOrderQuantity}.",
                ]);
            }

            // Step 3: enforce the maximum quantity rule when one exists.
            if ($maxOrderQuantity !== null && $quantity > (int) $maxOrderQuantity) {
                throw ValidationException::withMessages([
                    "quantity.$index" => "Quantity for item ".($index + 1)." must not exceed {$maxOrderQuantity}.",
                ]);
            }

            // Step 4: enforce lot-size multiples for operational packing rules.
            if ($lotSize > 1 && $quantity % $lotSize !== 0) {
                throw ValidationException::withMessages([
                    "quantity.$index" => "Quantity for item ".($index + 1)." must be in multiples of {$lotSize}.",
                ]);
            }
        } catch (Throwable $exception) {
            Log::error('Failed to validate quotation quantity rules.', ['item_index' => $index, 'quantity' => $quantity, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This reloads the saved quotation with the related data needed by the PDF.
    protected function loadQuotationForPdf(int $quotationId): Quotation
    {
        try {
            return Quotation::query()
                ->with([
                    'creator:id,name,email',
                    'ownerUser:id,name,email',
                    'ownerCompany:id,name,company_type',
                    'targetCompany:id,name,company_type',
                    'items' => fn ($query) => $query->orderBy('id'),
                ])
                ->findOrFail($quotationId);
        } catch (Throwable $exception) {
            Log::error('Failed to load quotation for PDF.', ['quotation_id' => $quotationId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}

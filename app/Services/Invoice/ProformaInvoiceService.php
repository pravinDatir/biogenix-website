<?php

namespace App\Services\Invoice;

use App\Models\Authorization\Company;
use App\Models\Authorization\User;
use App\Models\Invoice\ProformaInvoice;
use App\Models\Product\UserActivityLog;
use App\Services\Authorization\DataVisibilityService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProformaInvoiceService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
    ) {
    }

    // This prepares PI create-page data with visible products and available client companies.
    public function createPageData(?User $user, ?int $prefilledProductId = null): array
    {
        try {
            // Step 1: load visible products and attach the resolved price details used by PI.
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

            // Step 2: load allowed client companies for B2B users only.
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

            // Step 3: return the PI page data.
            return [
                'products' => $products,
                'clientCompanies' => $clientCompanies,
                'prefilledProductId' => $prefilledProductId,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build PI create page data.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads a product only when it is visible to the current user.
    public function findVisibleProduct(?User $user, int $productId): ?object
    {
        try {
            return $this->dataVisibilityService->visibleProductQuery($user)
                ->where('products.id', $productId)
                ->first();
        } catch (Throwable $exception) {
            Log::error('Failed to load visible PI product.', ['product_id' => $productId, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This prepares all requested PI items and applies price plus quantity rules.
    public function prepareProformaItems(array $validated, ?User $user): array
    {
        try {
            // Step 1: read all submitted product and quantity rows.
            $productIds = is_array($validated['product_id'] ?? null) ? $validated['product_id'] : [];
            $quantities = is_array($validated['quantity'] ?? null) ? $validated['quantity'] : [];
            $rowCount = max(count($productIds), count($quantities));
            $preparedItems = [];

            // Step 2: prepare each requested PI item one by one.
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

                $price = $this->dataVisibilityService->resolvePrice($productId, $user);

                if (! $price) {
                    throw ValidationException::withMessages([
                        "product_id.$index" => 'No visible price is configured for the selected product.',
                    ]);
                }

                $this->validateQuantityRules($quantity, $price, $index);
                $preparedItems[] = $this->buildPreparedItem($visibleProduct, $price, $quantity);
            }

            // Step 3: stop when no usable rows were submitted.
            if ($preparedItems === []) {
                throw ValidationException::withMessages([
                    'product_id' => 'Add at least one PI item.',
                ]);
            }

            return $preparedItems;
        } catch (Throwable $exception) {
            Log::error('Failed to prepare PI items.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This calculates the PI totals from all prepared item rows.
    public function calculateInvoiceTotals(array $preparedItems): array
    {
        try {
            // Step 1: sum all item-level pricing fields into invoice totals.
            return [
                'currency' => (string) ($preparedItems[0]['currency'] ?? 'INR'),
                'subtotal' => round(collect($preparedItems)->sum('line_subtotal'), 2),
                'tax_amount' => round(collect($preparedItems)->sum('line_tax_amount'), 2),
                'discount_amount' => round(collect($preparedItems)->sum('line_discount_amount'), 2),
                'price_after_gst' => round(collect($preparedItems)->sum('line_price_after_gst'), 2),
                'total_amount' => round(collect($preparedItems)->sum('line_total'), 2),
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to calculate PI totals.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This creates a PI and all requested line items inside one transaction.
    public function createProformaWithItems(
        array $validated,
        ?User $user,
        array $preparedItems,
        array $invoiceTotals,
        string $piNumber,
        string $guestSessionId,
    ): ProformaInvoice {
        try {
            return DB::transaction(function () use ($validated, $user, $preparedItems, $invoiceTotals, $piNumber, $guestSessionId): ProformaInvoice {
                // Step 1: create the PI header row with final invoice totals.
                $proforma = ProformaInvoice::query()->create([
                    'pi_number' => $piNumber,
                    'requester_type' => $user ? 'user' : 'guest',
                    'created_by_user_id' => $user?->id,
                    'owner_user_id' => $user?->id,
                    'owner_company_id' => $user?->company_id,
                    'target_type' => $validated['purpose'],
                    'target_name' => $validated['customer_name'],
                    'target_email' => $validated['customer_email'],
                    'target_phone' => $validated['customer_phone'] ?: null,
                    'target_company_id' => isset($validated['target_company_id']) ? (int) $validated['target_company_id'] : null,
                    'status' => 'draft',
                    'currency' => $invoiceTotals['currency'],
                    'subtotal' => $invoiceTotals['subtotal'],
                    'tax_amount' => $invoiceTotals['tax_amount'],
                    'discount_amount' => $invoiceTotals['discount_amount'],
                    'price_after_gst' => $invoiceTotals['price_after_gst'],
                    'total_amount' => $invoiceTotals['total_amount'],
                    'guest_session_id' => $user ? null : $guestSessionId,
                    'notes' => $validated['notes'] ?: null,
                ]);

                // Step 2: create one PI item row for each submitted product row.
                foreach ($preparedItems as $preparedItem) {
                    $proforma->items()->create($preparedItem);
                }

                // Step 3: return the PI with the data needed for PDF rendering.
                return $this->loadProformaForPdf($proforma->id);
            });
        } catch (Throwable $exception) {
            Log::error('Failed to create PI.', ['pi_number' => $piNumber, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This stores PI generation activity for guest and logged-in users.
    public function logPiGenerated(?User $user, string $sessionId, string $path, string $piNumber): void
    {
        try {
            UserActivityLog::query()->create([
                'session_id' => $sessionId,
                'user_id' => $user?->id,
                'user_type' => $user?->user_type ?: 'guest',
                'user_name' => $user?->name,
                'user_email' => $user?->email,
                'activity_type' => 'pi_generated',
                'path' => $path,
                'payload' => ['pi_number' => $piNumber],
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to log PI generation.', ['session_id' => $sessionId, 'user_id' => $user?->id, 'pi_number' => $piNumber, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns only proformas visible to the current user.
    public function listVisibleProformas(User $user): LengthAwarePaginator
    {
        try {
            return $this->dataVisibilityService->visibleProformaQuery($user)
                ->orderByDesc('pi.created_at')
                ->paginate(15);
        } catch (Throwable $exception) {
            Log::error('Failed to list visible PIs.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads one visible PI for the current user with all relations needed by the invoice PDF.
    public function findVisibleProforma(User $user, int $proformaId): ?ProformaInvoice
    {
        try {
            $visibleProformaId = $this->dataVisibilityService->visibleProformaQuery($user)
                ->where('pi.id', $proformaId)
                ->value('pi.id');

            if (! $visibleProformaId) {
                return null;
            }

            return $this->loadProformaForPdf((int) $visibleProformaId);
        } catch (Throwable $exception) {
            Log::error('Failed to load visible PI.', ['user_id' => $user->id, 'proforma_id' => $proformaId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This builds the downloadable PDF response for one PI using DomPDF only.
    public function downloadProformaPdf(ProformaInvoice $proforma): Response
    {
        try {
            // Step 1: render the invoice from the dedicated PDF blade view.
            $pdf = Pdf::loadView('invoice.invoice-pdf', [
                'proforma' => $proforma,
            ])->setPaper(
                config('invoice.pdf.paper', 'a4'),
                config('invoice.pdf.orientation', 'portrait'),
            );

            // Step 2: return the invoice as a direct file download.
            return $pdf->download($proforma->pi_number.'.pdf');
        } catch (Throwable $exception) {
            Log::error('Failed to build PI PDF.', ['proforma_id' => $proforma->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This builds one PI item row with price, GST, and discount snapshots.
    protected function buildPreparedItem(object $visibleProduct, array $price, int $quantity): array
    {
        try {
            // Step 1: calculate line-level pricing values from the selected price row.
            $discountPercent = 2.00;
            $unitPrice = (float) ($price['amount'] ?? 0);
            $unitTaxAmount = (float) ($price['tax_amount'] ?? 0);
            $unitPriceAfterGst = (float) ($price['price_after_gst'] ?? 0);
            $unitDiscountAmount = round(($unitPriceAfterGst * $discountPercent) / 100, 2);
            $lineSubtotal = round($unitPrice * $quantity, 2);
            $lineTaxAmount = round($unitTaxAmount * $quantity, 2);
            $linePriceAfterGst = round($unitPriceAfterGst * $quantity, 2);
            $lineDiscountAmount = round($unitDiscountAmount * $quantity, 2);
            $lineTotal = round($linePriceAfterGst - $lineDiscountAmount, 2);

            // Step 2: return the full PI item payload used by the create transaction.
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
            Log::error('Failed to build PI item payload.', ['product_id' => $visibleProduct->id ?? null, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This validates min quantity, max quantity, and lot size before PI save.
    protected function validateQuantityRules(int $quantity, array $price, int $index): void
    {
        try {
            // Step 1: read the resolved quantity rules from the selected price row.
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

            // Step 4: enforce the lot size rule when quantity must come in multiples.
            if ($lotSize > 1 && $quantity % $lotSize !== 0) {
                throw ValidationException::withMessages([
                    "quantity.$index" => "Quantity for item ".($index + 1)." must be in multiples of {$lotSize}.",
                ]);
            }
        } catch (Throwable $exception) {
            Log::error('Failed to validate PI quantity rules.', ['item_index' => $index, 'quantity' => $quantity, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads the saved PI with related rows needed by the invoice template.
    protected function loadProformaForPdf(int $proformaId): ProformaInvoice
    {
        try {
            return ProformaInvoice::query()
                ->with([
                    'creator:id,name,email',
                    'ownerUser:id,name,email',
                    'ownerCompany:id,name,company_type',
                    'targetCompany:id,name,company_type',
                    'items' => fn ($query) => $query->orderBy('id'),
                ])
                ->findOrFail($proformaId);
        } catch (Throwable $exception) {
            Log::error('Failed to load PI for PDF.', ['proforma_id' => $proformaId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}

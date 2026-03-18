<?php

namespace App\Services\Invoice;

use App\Models\Authorization\Company;
use App\Models\Authorization\User;
use App\Models\Invoice\ProformaInvoice;
use App\Models\Product\Product;
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

            // Step 2: when pricing setup is still incomplete, keep the PI request page usable by loading active products with basic variant details.
            if ($products->isEmpty()) {
                $products = $this->loadFallbackQuotationProducts();
            }

            // Step 3: load allowed client companies for B2B users only.
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

            // Step 4: return the PI page data.
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

    // This keeps the PI request page searchable even before the pricing master is fully configured.
    protected function loadFallbackQuotationProducts()
    {
        try {
            // Step 1: load active products with their first active sellable variant so the request page can still identify catalogue items.
            return Product::query()
                ->with([
                    'variants' => fn ($builder) => $builder->where('is_active', true)->orderBy('id'),
                ])
                ->where('is_active', true)
                ->orderBy('name')
                ->get()
                ->map(function (Product $product) {
                    // Step 2: use the first active variant as the basic operational source for SKU and buying rules.
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
            Log::error('Failed to load fallback PI products.', ['error' => $exception->getMessage()]);
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

                $price = $this->dataVisibilityService->resolvePrice($productId, $user, $quantity);

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
            // Step 1: save the instantly generated quotation with a draft status so the PDF can be downloaded right away.
            return $this->saveProformaSnapshotWithItems(
                $validated,
                $user,
                $preparedItems,
                $invoiceTotals,
                $piNumber,
                'draft',
                $guestSessionId,
                true,
            );
        } catch (Throwable $exception) {
            Log::error('Failed to create PI.', ['pi_number' => $piNumber, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This stores a PI request in pending review status so the business team can verify it before issuing the final PI.
    public function createPendingPiRequestWithItems(
        array $validated,
        ?User $user,
        array $preparedItems,
        array $invoiceTotals,
        string $requestReference,
        string $guestSessionId,
    ): ProformaInvoice {
        try {
            // Step 1: save the request snapshot with pending review status instead of issuing the final PDF immediately.
            return $this->saveProformaSnapshotWithItems(
                $validated,
                $user,
                $preparedItems,
                $invoiceTotals,
                $requestReference,
                'pending_review',
                $guestSessionId,
                false,
            );
        } catch (Throwable $exception) {
            Log::error('Failed to create PI request.', ['request_reference' => $requestReference, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
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

    // This stores PI request activity so the business team can later track request submission events.
    public function logPiRequestSubmitted(?User $user, string $sessionId, string $path, string $requestReference): void
    {
        try {
            UserActivityLog::query()->create([
                'session_id' => $sessionId,
                'user_id' => $user?->id,
                'user_type' => $user?->user_type ?: 'guest',
                'user_name' => $user?->name,
                'user_email' => $user?->email,
                'activity_type' => 'pi_request_submitted',
                'path' => $path,
                'payload' => ['request_reference' => $requestReference],
                'created_at' => now(),
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to log PI request submission.', ['session_id' => $sessionId, 'user_id' => $user?->id, 'request_reference' => $requestReference, 'error' => $exception->getMessage()]);
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

    // This tells the UI and controller whether a PI record is ready to be downloaded as a final document.
    public function isReadyForPdfDownload(ProformaInvoice $proforma): bool
    {
        try {
            // Step 1: pending review records are requests only, so they must stay non-downloadable until the team issues the final PI.
            return ! in_array(strtolower((string) $proforma->status), ['pending_review', 'requested', 'submitted'], true);
        } catch (Throwable $exception) {
            Log::error('Failed to check PI download readiness.', ['proforma_id' => $proforma->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This builds one PI item row with price, GST, and discount snapshots.
    protected function buildPreparedItem(object $visibleProduct, array $price, int $quantity): array
    {
        try {
            // Step 1: calculate line-level pricing values from the shared resolved price so PI follows the same rules as cart and checkout.
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

    // This validates min quantity, max quantity, and lot size before PI save using the selected variant rules.
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

    // This saves one proforma snapshot with all items so both instant quotation and pending PI request flows share the same write logic.
    protected function saveProformaSnapshotWithItems(
        array $validated,
        ?User $user,
        array $preparedItems,
        array $invoiceTotals,
        string $documentReference,
        string $status,
        string $guestSessionId,
        bool $loadPdfRelations,
    ): ProformaInvoice {
        try {
            return DB::transaction(function () use ($validated, $user, $preparedItems, $invoiceTotals, $documentReference, $status, $guestSessionId, $loadPdfRelations): ProformaInvoice {
                // Step 1: save the request or quotation header so the business team has the submitted customer and pricing snapshot.
                $proforma = ProformaInvoice::query()->create([
                    'pi_number' => $documentReference,
                    'requester_type' => $user ? 'user' : 'guest',
                    'created_by_user_id' => $user?->id,
                    'owner_user_id' => $user?->id,
                    'owner_company_id' => $user?->company_id,
                    'target_type' => $validated['purpose'],
                    'target_name' => $validated['customer_name'],
                    'target_email' => $validated['customer_email'],
                    'target_phone' => $validated['customer_phone'] ?: null,
                    'target_company_id' => isset($validated['target_company_id']) ? (int) $validated['target_company_id'] : null,
                    'status' => $status,
                    'currency' => $invoiceTotals['currency'],
                    'subtotal' => $invoiceTotals['subtotal'],
                    'tax_amount' => $invoiceTotals['tax_amount'],
                    'discount_amount' => $invoiceTotals['discount_amount'],
                    'price_after_gst' => $invoiceTotals['price_after_gst'],
                    'total_amount' => $invoiceTotals['total_amount'],
                    'guest_session_id' => $user ? null : $guestSessionId,
                    'notes' => $validated['notes'] ?: null,
                ]);

                // Step 2: save the submitted product lines so the team can review the exact requested quantities and pricing snapshot.
                foreach ($preparedItems as $preparedItem) {
                    $proforma->items()->create($preparedItem);
                }

                // Step 3: return the record in the shape needed by the calling flow.
                if ($loadPdfRelations) {
                    return $this->loadProformaForPdf($proforma->id);
                }

                return $proforma->load([
                    'creator:id,name,email',
                    'ownerUser:id,name,email',
                    'ownerCompany:id,name,company_type',
                    'targetCompany:id,name,company_type',
                    'items' => fn ($query) => $query->orderBy('id'),
                ]);
            });
        } catch (Throwable $exception) {
            Log::error('Failed to save proforma snapshot.', ['document_reference' => $documentReference, 'status' => $status, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}

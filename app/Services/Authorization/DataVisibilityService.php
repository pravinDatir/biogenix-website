<?php

namespace App\Services\Authorization;

use App\Models\Authorization\B2bClientAssignment;
use App\Models\Authorization\DelegatedAdminScope;
use App\Models\Authorization\User;
use App\Models\Invoice\ProformaInvoice;
use App\Models\Product\Product;
use App\Models\Product\ProductPrice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Throwable;

class DataVisibilityService
{
    public function __construct(
        protected RolePermissionService $rolePermissionService,
    ) {
    }

    // This returns the allowed product visibility scopes for the current user.
    public function productScopes(?User $user): array
    {
        try {
            if (! $user) {
                return ['public', 'all'];
            }

            if ($this->isFullAdmin($user) || $this->isDelegatedAdmin($user)) {
                return ['public', 'b2c', 'b2b', 'internal', 'all'];
            }

            return match ($user->user_type) {
                'b2b' => ['public', 'b2b', 'all'],
                'internal' => ['public', 'internal', 'all'],
                default => ['public', 'b2c', 'all'],
            };
        } catch (Throwable $exception) {
            Log::error('Failed to resolve product scopes.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This builds the base visible product query using product relations and variant-price scope checks.
    public function visibleProductQuery(?User $user): Builder
    {
        try {
            $allowedPriceTypes = $this->pricePriority($user);

            return Product::query()
                ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
                ->leftJoin('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
                ->leftJoin('product_image', 'product_image.id', '=', 'products.product_image_id')
                ->select([
                    'products.*',
                    'categories.name as category_name',
                    'categories.slug as category_slug',
                    'subcategories.name as subcategory_name',
                    'subcategories.slug as subcategory_slug',
                    'product_image.file_path as image_path',
                ])
                ->where('is_active', true)
                ->whereIn('visibility_scope', $this->productScopes($user))
                ->whereHas('variants', function (Builder $variantQuery) use ($allowedPriceTypes): void {
                    $variantQuery->where('is_active', true)
                        ->whereHas('prices', function (Builder $priceQuery) use ($allowedPriceTypes): void {
                            $priceQuery->where('is_active', true)
                                ->whereIn('price_type', $allowedPriceTypes);
                        });
                });
        } catch (Throwable $exception) {
            Log::error('Failed to build visible product query.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This resolves the first visible price for a product after user type and company rules are applied.
    public function resolvePrice(int $productId, ?User $user): ?array
    {
        try {
            $baseQuery = ProductPrice::query()
                ->join('product_variants', 'product_variants.id', '=', 'product_prices.product_variant_id')
                ->where('product_variants.product_id', $productId)
                ->where('product_variants.is_active', true)
                ->where('product_prices.is_active', true)
                ->select([
                    'product_prices.amount',
                    'product_prices.DiscountType as discount_type',
                    'product_prices.Discount as discount_value',
                    'product_prices.gst_rate',
                    'product_prices.tax_amount',
                    'product_prices.price_after_gst',
                    'product_prices.currency',
                    'product_prices.min_order_quantity',
                    'product_prices.max_order_quantity',
                    'product_prices.lot_size',
                    'product_prices.price_type',
                    'product_variants.id as product_variant_id',
                    'product_variants.sku as variant_sku',
                    'product_variants.variant_name',
                ]);

            if ($user && $user->isB2b() && $user->company_id) {
                $companyPrice = (clone $baseQuery)
                    ->where('product_prices.price_type', 'company_price')
                    ->where('product_prices.company_id', $user->company_id)
                    ->orderBy('product_variants.id')
                    ->orderBy('product_prices.id')
                    ->first();

                if ($companyPrice) {
                    return $this->formatResolvedPrice($companyPrice, $productId, $user);
                }
            }

            foreach (array_values(array_filter($this->pricePriority($user), fn ($type) => $type !== 'company_price')) as $priceType) {
                $price = (clone $baseQuery)
                    ->where('product_prices.price_type', $priceType)
                    ->whereNull('product_prices.company_id')
                    ->orderBy('product_variants.id')
                    ->orderBy('product_prices.id')
                    ->first();

                if ($price) {
                    return $this->formatResolvedPrice($price, $productId, $user);
                }
            }

            return null;
        } catch (Throwable $exception) {
            Log::error('Failed to resolve product price.', ['product_id' => $productId, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This resolves the first visible price for one exact product variant.
    public function resolveVariantPrice(int $productVariantId, ?User $user): ?array
    {
        try {
            // Step 1: build the base price query for the exact selected variant.
            $baseQuery = ProductPrice::query()
                ->join('product_variants', 'product_variants.id', '=', 'product_prices.product_variant_id')
                ->where('product_variants.id', $productVariantId)
                ->where('product_variants.is_active', true)
                ->where('product_prices.is_active', true)
                ->select([
                    'product_prices.amount',
                    'product_prices.DiscountType as discount_type',
                    'product_prices.Discount as discount_value',
                    'product_prices.gst_rate',
                    'product_prices.tax_amount',
                    'product_prices.price_after_gst',
                    'product_prices.currency',
                    'product_prices.min_order_quantity',
                    'product_prices.max_order_quantity',
                    'product_prices.lot_size',
                    'product_prices.price_type',
                    'product_variants.product_id',
                    'product_variants.id as product_variant_id',
                    'product_variants.sku as variant_sku',
                    'product_variants.variant_name',
                ]);

            // Step 2: check company-specific price first for B2B users.
            if ($user && $user->isB2b() && $user->company_id) {
                $companyPrice = (clone $baseQuery)
                    ->where('product_prices.price_type', 'company_price')
                    ->where('product_prices.company_id', $user->company_id)
                    ->orderBy('product_prices.id')
                    ->first();

                if ($companyPrice) {
                    return $this->formatResolvedPrice($companyPrice, (int) $companyPrice->product_id, $user);
                }
            }

            // Step 3: resolve the first matching non-company price by current price priority.
            foreach (array_values(array_filter($this->pricePriority($user), fn ($type) => $type !== 'company_price')) as $priceType) {
                $price = (clone $baseQuery)
                    ->where('product_prices.price_type', $priceType)
                    ->whereNull('product_prices.company_id')
                    ->orderBy('product_prices.id')
                    ->first();

                if ($price) {
                    return $this->formatResolvedPrice($price, (int) $price->product_id, $user);
                }
            }

            // Step 4: return null when no visible price exists for the variant.
            return null;
        } catch (Throwable $exception) {
            Log::error('Failed to resolve variant price.', ['product_variant_id' => $productVariantId, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks whether the user can generate PI for other customers.
    public function canGeneratePiForOther(?User $user): bool
    {
        try {
            if (! $user) {
                return true;
            }

            if ($this->isFullAdmin($user)) {
                return true;
            }

            if ($user->isB2c()) {
                return false;
            }

            return $this->rolePermissionService->hasPermission($user, 'pi.generate.other.client');
        } catch (Throwable $exception) {
            Log::error('Failed to check PI permission.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks whether the user can access data for the given company.
    public function canAccessCompanyData(User $user, ?int $companyId): bool
    {
        try {
            if (! $companyId) {
                return true;
            }

            if ($this->isFullAdmin($user)) {
                return true;
            }

            if ($this->isDelegatedAdmin($user)) {
                return in_array($companyId, $this->delegatedAdminCompanyScopeIds($user), true);
            }

            if ($user->company_id === $companyId) {
                return true;
            }

            return in_array($companyId, $this->assignedClientCompanyIds($user), true);
        } catch (Throwable $exception) {
            Log::error('Failed to check company access.', ['user_id' => $user->id, 'company_id' => $companyId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns assigned client company IDs for a B2B user.
    public function assignedClientCompanyIds(User $user): array
    {
        try {
            return B2bClientAssignment::query()
                ->where('b2b_user_id', $user->id)
                ->pluck('client_company_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        } catch (Throwable $exception) {
            Log::error('Failed to load assigned client companies.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This builds the base visible PI query for the current user.
    public function visibleProformaQuery(User $user): Builder
    {
        try {
            $query = ProformaInvoice::query()
                ->from('proforma_invoices as pi')
                ->leftJoin('users as owners', 'owners.id', '=', 'pi.owner_user_id')
                ->leftJoin('companies as owner_companies', 'owner_companies.id', '=', 'pi.owner_company_id')
                ->leftJoin('companies as target_companies', 'target_companies.id', '=', 'pi.target_company_id')
                ->select([
                    'pi.*',
                    'owners.name as owner_name',
                    'owner_companies.name as owner_company_name',
                    'target_companies.name as target_company_name',
                ]);

            if ($this->isFullAdmin($user)) {
                return $query;
            }

            if ($this->isDelegatedAdmin($user)) {
                $allowedCompanyIds = $this->delegatedAdminCompanyScopeIds($user);

                return $query->where(function (Builder $builder) use ($user, $allowedCompanyIds): void {
                    $builder->where('pi.owner_user_id', $user->id);

                    if ($allowedCompanyIds !== []) {
                        $builder->orWhereIn('pi.owner_company_id', $allowedCompanyIds)
                            ->orWhereIn('pi.target_company_id', $allowedCompanyIds);
                    }
                });
            }

            if ($user->isB2c()) {
                return $query->where('pi.owner_user_id', $user->id);
            }

            if ($user->isB2b()) {
                $assignedClientIds = $this->assignedClientCompanyIds($user);

                return $query->where(function (Builder $builder) use ($user, $assignedClientIds): void {
                    $builder->where('pi.owner_user_id', $user->id);

                    if ($user->company_id) {
                        $builder->orWhere('pi.owner_company_id', $user->company_id);
                    }

                    if ($assignedClientIds !== []) {
                        $builder->orWhereIn('pi.target_company_id', $assignedClientIds);
                    }
                });
            }

            return $query->where('pi.owner_user_id', $user->id);
        } catch (Throwable $exception) {
            Log::error('Failed to build visible PI query.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns price priority from most-specific to least-specific for the current user.
    protected function pricePriority(?User $user): array
    {
        try {
            if (! $user) {
                return ['retail', 'public'];
            }

            if ($this->isFullAdmin($user) || $this->isDelegatedAdmin($user)) {
                return ['company_price', 'logged_in', 'dealer', 'institutional', 'retail', 'public'];
            }

            if ($user->isB2c()) {
                return ['logged_in', 'retail', 'public'];
            }

            if ($user->isB2b()) {
                if (in_array($user->b2b_type, ['dealer', 'distributor'], true)) {
                    return ['company_price', 'dealer', 'logged_in', 'public', 'retail'];
                }

                if (in_array($user->b2b_type, ['lab', 'hospital'], true)) {
                    return ['company_price', 'institutional', 'logged_in', 'public', 'retail'];
                }

                return ['company_price', 'dealer', 'institutional', 'logged_in', 'public', 'retail'];
            }

            return ['retail', 'public'];
        } catch (Throwable $exception) {
            Log::error('Failed to resolve price priority.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns company IDs assigned to the delegated admin scope.
    public function delegatedAdminCompanyScopeIds(User $user): array
    {
        try {
            if (! $this->isDelegatedAdmin($user)) {
                return [];
            }

            return DelegatedAdminScope::query()
                ->where('delegated_admin_user_id', $user->id)
                ->where('scope_type', 'company')
                ->pluck('scope_value')
                ->map(fn ($scopeValue) => (int) $scopeValue)
                ->filter(fn ($scopeValue) => $scopeValue > 0)
                ->values()
                ->all();
        } catch (Throwable $exception) {
            Log::error('Failed to load delegated admin scopes.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks whether the user is a full admin.
    protected function isFullAdmin(User $user): bool
    {
        try {
            return $this->rolePermissionService->hasRole($user, 'admin') || $user->user_type === 'admin';
        } catch (Throwable $exception) {
            Log::error('Failed to check full-admin state.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks whether the user is a delegated admin.
    protected function isDelegatedAdmin(User $user): bool
    {
        try {
            return $this->rolePermissionService->hasRole($user, 'delegated_admin') || $user->user_type === 'delegated_admin';
        } catch (Throwable $exception) {
            Log::error('Failed to check delegated-admin state.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This formats the resolved price row into the response shape expected by callers.
    protected function formatResolvedPrice(object $price, int $productId, ?User $user): array
    {
        try {
            // Read the saved base price before any discount is applied.
            $baseAmount = round((float) $price->amount, 2);

            // Calculate the usable discount values from the configured type and value.
            $discountDetails = $this->getDiscountDetails($baseAmount, $price->discount_type ?? null, $price->discount_value ?? null);

            // Recalculate tax on the discounted base price so the totals stay correct.
            $gstRate = round((float) $price->gst_rate, 2);
            $taxAmount = round(($discountDetails['final_amount'] * $gstRate) / 100, 2);
            $priceAfterGst = round($discountDetails['final_amount'] + $taxAmount, 2);

            return [
                'base_amount' => $baseAmount,
                'amount' => $discountDetails['final_amount'],
                'gst_rate' => $gstRate,
                'tax_amount' => $taxAmount,
                'price_after_gst' => $priceAfterGst,
                'currency' => (string) $price->currency,
                'discount_type' => $discountDetails['discount_type'],
                'discount_value' => $discountDetails['discount_value'],
                'discount_amount' => $discountDetails['discount_amount'],
                'min_order_quantity' => (int) $price->min_order_quantity,
                'max_order_quantity' => $price->max_order_quantity === null ? null : (int) $price->max_order_quantity,
                'lot_size' => (int) $price->lot_size,
                'price_type' => (string) $price->price_type,
                'product_variant_id' => (int) $price->product_variant_id,
                'variant_sku' => (string) $price->variant_sku,
                'variant_name' => (string) $price->variant_name,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to format resolved price.', ['product_id' => $productId, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This calculates one safe discount result from the configured discount type and value.
    protected function getDiscountDetails(float $baseAmount, mixed $discountType, mixed $discountValue): array
    {
        // Keep the stored discount type limited to the supported values only.
        $finalDiscountType = strtolower(trim((string) $discountType));

        if (! in_array($finalDiscountType, ['cash', 'percent'], true)) {
            $finalDiscountType = 'cash';
        }

        // Ignore negative discounts and keep the saved value easy to reason about.
        $finalDiscountValue = round(max(0, (float) $discountValue), 2);

        // Convert percentage discounts into their matching amount from the base price.
        if ($finalDiscountType === 'percent') {
            $finalDiscountValue = min($finalDiscountValue, 100);
            $discountAmount = round(($baseAmount * $finalDiscountValue) / 100, 2);
        } else {
            $discountAmount = $finalDiscountValue;
        }

        // Never allow the discount to reduce the price below zero.
        $discountAmount = min($discountAmount, $baseAmount);

        // This is the final base price that the customer should pay before GST.
        $finalAmount = round($baseAmount - $discountAmount, 2);

        return [
            'discount_type' => $finalDiscountType,
            'discount_value' => $finalDiscountValue,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
        ];
    }
}

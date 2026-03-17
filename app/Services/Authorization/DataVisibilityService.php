<?php

namespace App\Services\Authorization;

use App\Models\Authorization\B2bClientAssignment;
use App\Models\Authorization\DelegatedAdminScope;
use App\Models\Authorization\User;
use App\Models\Invoice\ProformaInvoice;
use App\Models\Product\Product;
use App\Services\Pricing\PriceService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Throwable;

class DataVisibilityService
{
    public function __construct(
        protected RolePermissionService $rolePermissionService,
        protected PriceService $priceService,
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
            // Business step: read the visible price ladder once so the storefront query only considers purchasable products.
            $allowedPriceTypes = $this->priceService->visiblePriceTypes($user);
            $genericPriceTypes = array_values(array_filter($allowedPriceTypes, fn ($type) => $type !== 'company_price'));

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
                ->whereHas('variants', function (Builder $variantQuery) use ($genericPriceTypes, $user): void {
                    $variantQuery->where('is_active', true)
                        ->whereHas('prices', function (Builder $priceQuery) use ($genericPriceTypes, $user): void {
                            $priceQuery->where('is_active', true)
                                ->where(function (Builder $visiblePriceQuery) use ($genericPriceTypes, $user): void {
                                    // Business step: generic price rows are shared price ladders for all qualifying shoppers.
                                    if ($genericPriceTypes !== []) {
                                        $visiblePriceQuery->where(function (Builder $genericPriceQuery) use ($genericPriceTypes): void {
                                            $genericPriceQuery
                                                ->whereNull('company_id')
                                                ->whereIn('price_type', $genericPriceTypes);
                                        });
                                    }

                                    // Business step: company prices are only visible when the logged-in B2B shopper belongs to that company.
                                    if ($user && $user->isB2b() && $user->company_id) {
                                        $visiblePriceQuery->orWhere(function (Builder $companyPriceQuery) use ($user): void {
                                            $companyPriceQuery
                                                ->where('price_type', 'company_price')
                                                ->where('company_id', $user->company_id);
                                        });
                                    }
                                });
                        });
                });
        } catch (Throwable $exception) {
            Log::error('Failed to build visible product query.', ['user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This resolves the first visible price for a product after user type and company rules are applied.
    public function resolvePrice(int $productId, ?User $user, int $quantity = 1, ?string $couponCode = null): ?array
    {
        try {
            // Business step: delegate the final pricing decision to the shared pricing service so every flow uses one rule engine.
            return $this->priceService->resolveProductPrice($productId, $user, $quantity, $couponCode);
        } catch (Throwable $exception) {
            Log::error('Failed to resolve product price.', ['product_id' => $productId, 'user_id' => $user?->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This resolves the first visible price for one exact product variant.
    public function resolveVariantPrice(int $productVariantId, ?User $user, int $quantity = 1, ?string $couponCode = null): ?array
    {
        try {
            // Business step: delegate the final variant price to the shared pricing service so cart, PI, and order flows stay aligned.
            return $this->priceService->resolveVariantPrice($productVariantId, $user, $quantity, $couponCode);
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
            // Business step: reuse the shared pricing service so visibility and final pricing stay on the same ladder definition.
            return $this->priceService->visiblePriceTypes($user);
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

}

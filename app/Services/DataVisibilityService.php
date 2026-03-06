<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataVisibilityService
{
    public function __construct(
        protected RolePermissionService $rolePermissionService,
    ) {
    }

    public function productScopes(?User $user): array
    {
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
    }

    public function visibleProductQuery(?User $user): Builder
    {
        $query = DB::table('products')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->leftJoin('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
            ->leftJoin('product_image', 'product_image.id', '=', 'products.product_image_id')
            ->leftJoin('product_prices', 'product_prices.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.sku',
                'products.name',
                'products.brand',
                'products.slug',
                'products.description',
                'products.category_id',
                'products.subcategory_id',
                'products.visibility_scope',
                'products.is_active',
                'products.updated_at',
                'categories.name as category_name',
                'subcategories.name as subcategory_name',
                'product_image.file_path as image_path',
                'product_prices.amount as price_amount',
                'product_prices.currency as price_currency',
                'product_prices.price_type as price_type',
            )
            ->where('products.is_active', true)
            ->whereIn('products.visibility_scope', $this->productScopes($user))
            ->whereIn('product_prices.price_type', $this->pricePriority($user));

        if ($this->hasProductsColumn('is_published')) {
            $query->where('products.is_published', true);
        }

        return $query;
    }

    protected function hasProductsColumn(string $column): bool
    {
        static $cache = [];

        if (! array_key_exists($column, $cache)) {
            $cache[$column] = DB::getSchemaBuilder()->hasColumn('products', $column);
        }

        return $cache[$column];
    }


    /**
     * @return array{amount: float, currency: string, price_type: string}|null
     */
    public function resolvePrice(int $productId, ?User $user): ?array
    {
        $priority = $this->pricePriority($user);
        $priorityRank = array_flip($priority);

        $price = DB::table('product_prices')
            ->where('product_id', $productId)
            ->whereIn('price_type', $priority)
            ->get(['amount', 'currency', 'price_type'])
            ->sortBy(fn ($row) => $priorityRank[$row->price_type] ?? PHP_INT_MAX)
            ->first();

        if (! $price) {
            return null;
        }

        Log::info('Resolved product price', [
            'product_id' => $productId,
            'user_id' => $user?->id,
            'amount' => (float) $price->amount,
            'currency' => $price->currency,
            'price_type' => $price->price_type,
        ]);

        return [
            'amount' => (float) $price->amount,
            'currency' => $price->currency,
            'price_type' => $price->price_type,
        ];
    }

    public function canGeneratePiForOther(?User $user): bool
    {
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
    }

    public function canAccessCompanyData(User $user, ?int $companyId): bool
    {
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
    }

    public function assignedClientCompanyIds(User $user): array
    {
        return DB::table('b2b_client_assignments')
            ->where('b2b_user_id', $user->id)
            ->pluck('client_company_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function visibleProformaQuery(User $user): Builder
    {
        $query = DB::table('proforma_invoices as pi')
            ->leftJoin('users as owners', 'owners.id', '=', 'pi.owner_user_id')
            ->leftJoin('companies as owner_companies', 'owner_companies.id', '=', 'pi.owner_company_id')
            ->leftJoin('companies as target_companies', 'target_companies.id', '=', 'pi.target_company_id')
            ->select(
                'pi.id',
                'pi.pi_number',
                'pi.requester_type',
                'pi.target_type',
                'pi.target_name',
                'pi.status',
                'pi.total_amount',
                'pi.created_at',
                'owners.name as owner_name',
                'owner_companies.name as owner_company_name',
                'target_companies.name as target_company_name',
            );

        if ($this->isFullAdmin($user)) {
            return $query;
        }

        if ($this->isDelegatedAdmin($user)) {
            $allowedCompanyIds = $this->delegatedAdminCompanyScopeIds($user);

            return $query->where(function (Builder $builder) use ($user, $allowedCompanyIds): void {
                $builder->where('pi.owner_user_id', $user->id);

                if (! empty($allowedCompanyIds)) {
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

                if (! empty($assignedClientIds)) {
                    $builder->orWhereIn('pi.target_company_id', $assignedClientIds);
                }
            });
        }

        return $query->where('pi.owner_user_id', $user->id);
    }

    protected function pricePriority(?User $user): array
    {
        if (! $user) {
            return ['public'];
        }

        if ($this->isFullAdmin($user) || $this->isDelegatedAdmin($user)) {
            return [ 'dealer', 'institutional', 'retail', 'public'];
        }

        if ($user->isB2c()) {
            return ['retail', 'public'];
        }

        if ($user->isB2b()) {
            if (in_array($user->b2b_type, ['dealer', 'distributor'], true)) {
                return ['contract', 'dealer', 'public'];
            }

            if (in_array($user->b2b_type, ['lab', 'hospital'], true)) {
                return ['contract', 'institutional', 'public'];
            }

            return ['contract', 'dealer', 'institutional', 'public'];
        }

        return ['public'];
    }

    public function delegatedAdminCompanyScopeIds(User $user): array
    {
        if (! $this->isDelegatedAdmin($user)) {
            return [];
        }

        return DB::table('delegated_admin_scopes')
            ->where('delegated_admin_user_id', $user->id)
            ->where('scope_type', 'company')
            ->pluck('scope_value')
            ->map(fn ($scopeValue) => (int) $scopeValue)
            ->filter(fn ($scopeValue) => $scopeValue > 0)
            ->values()
            ->all();
    }

    protected function isFullAdmin(User $user): bool
    {
        return $this->rolePermissionService->hasRole($user, 'admin')
            || $user->user_type === 'admin';
    }

    protected function isDelegatedAdmin(User $user): bool
    {
        return $this->rolePermissionService->hasRole($user, 'delegated_admin')
            || $user->user_type === 'delegated_admin';
    }
}

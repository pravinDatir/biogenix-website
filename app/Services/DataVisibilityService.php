<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

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
        return DB::table('products')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->select(
                'products.id',
                'products.sku',
                'products.name',
                'products.description',
                'products.visibility_scope',
                'categories.name as category_name',
            )
            ->where('products.is_active', true)
            ->whereIn('products.visibility_scope', $this->productScopes($user));
    }

    /**
     * @return array{amount: float, currency: string, price_type: string}|null
     */
    public function resolvePrice(int $productId, ?User $user): ?array
    {
        if ($user && $user->company_id) {
            $contractPrice = DB::table('product_prices')
                ->where('product_id', $productId)
                ->where('price_type', 'contract')
                ->where('company_id', $user->company_id)
                ->orderByDesc('id')
                ->first();

            if ($contractPrice) {
                return [
                    'amount' => (float) $contractPrice->amount,
                    'currency' => $contractPrice->currency,
                    'price_type' => $contractPrice->price_type,
                ];
            }
        }

        foreach ($this->pricePriority($user) as $priceType) {
            $price = DB::table('product_prices')
                ->where('product_id', $productId)
                ->where('price_type', $priceType)
                ->whereNull('company_id')
                ->orderByDesc('id')
                ->first();

            if ($price) {
                return [
                    'amount' => (float) $price->amount,
                    'currency' => $price->currency,
                    'price_type' => $price->price_type,
                ];
            }
        }

        return null;
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
            return ['contract', 'dealer', 'institutional', 'retail', 'public'];
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

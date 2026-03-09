<?php

namespace App\Services\Authorization;

use App\Models\Authorization\Company;
use App\Models\Authorization\DelegatedAdminScope;
use App\Models\Authorization\Department;
use App\Models\Authorization\Permission;
use App\Models\Authorization\Role;
use App\Models\Authorization\User;
use App\Models\Authorization\UserPermission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class AdminUserManagementService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
        protected RolePermissionService $rolePermissionService,
    ) {
    }

    // This prepares all admin user-management page data in one place.
    public function indexData(User $currentUser): array
    {
        try {
            $delegatedScopeCompanyIds = $this->dataVisibilityService->delegatedAdminCompanyScopeIds($currentUser);

            $pendingB2bUsers = User::query()
                ->with('company:id,name')
                ->where('user_type', 'b2b')
                ->where('status', 'pending_approval')
                ->orderBy('created_at')
                ->get(['id', 'name', 'email', 'b2b_type', 'created_at', 'company_id'])
                ->map(function (User $user) {
                    $user->company_name = $user->company?->name;
                    return $user;
                });

            $departments = Department::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            $permissions = Permission::query()
                ->orderBy('slug')
                ->get();

            $users = User::query()
                ->select('id', 'name', 'email', 'user_type', 'status', 'company_id')
                ->latest('id')
                ->limit(200)
                ->get();

            $delegatedAdmins = User::query()
                ->select('id', 'name', 'email')
                ->whereHas('roles', fn ($query) => $query->where('slug', 'delegated_admin'))
                ->orderBy('name')
                ->get();

            $companiesQuery = Company::query()
                ->select('id', 'name')
                ->orderBy('name');

            if ($this->rolePermissionService->hasRole($currentUser, 'delegated_admin')
                && ! $this->rolePermissionService->hasRole($currentUser, 'admin')) {
                $companiesQuery->whereIn('id', $delegatedScopeCompanyIds ?: [-1]);
            }

            $companies = $companiesQuery->get();

            $userOverrides = UserPermission::query()
                ->with([
                    'user:id,name,email',
                    'permission:id,slug',
                    'grantedBy:id,name',
                ])
                ->latest('id')
                ->limit(200)
                ->get()
                ->map(function (UserPermission $override) {
                    $override->user_name = $override->user?->name;
                    $override->user_email = $override->user?->email;
                    $override->permission_slug = $override->permission?->slug;
                    $override->granted_by_name = $override->grantedBy?->name;
                    return $override;
                });

            $delegatedScopes = $this->mapDelegatedScopes(
                DelegatedAdminScope::query()
                    ->with([
                        'delegatedAdmin:id,name,email',
                        'assignedBy:id,name',
                    ])
                    ->latest('id')
                    ->limit(200)
                    ->get(),
            );

            return [
                'pendingB2bUsers' => $pendingB2bUsers,
                'departments' => $departments,
                'permissions' => $permissions,
                'users' => $users,
                'delegatedAdmins' => $delegatedAdmins,
                'companies' => $companies,
                'userOverrides' => $userOverrides,
                'delegatedScopes' => $delegatedScopes,
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build admin user management data.', ['user_id' => $currentUser->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This approves a pending B2B user and records the approving admin.
    public function approveB2bUser(int $userId, int $approvedByUserId): int
    {
        try {
            return User::query()
                ->whereKey($userId)
                ->where('user_type', 'b2b')
                ->where('status', 'pending_approval')
                ->update([
                    'status' => 'active',
                    'approved_at' => now(),
                    'approved_by_user_id' => $approvedByUserId,
                ]);
        } catch (Throwable $exception) {
            Log::error('Failed to approve B2B user.', ['user_id' => $userId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This rejects a pending B2B user and marks the account as blocked.
    public function rejectB2bUser(int $userId, int $approvedByUserId): int
    {
        try {
            return User::query()
                ->whereKey($userId)
                ->where('user_type', 'b2b')
                ->where('status', 'pending_approval')
                ->update([
                    'status' => 'blocked',
                    'approved_at' => now(),
                    'approved_by_user_id' => $approvedByUserId,
                ]);
        } catch (Throwable $exception) {
            Log::error('Failed to reject B2B user.', ['user_id' => $userId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This creates an internal user and assigns the matching department roles.
    public function createInternalUser(array $validated, int $actorUserId): void
    {
        try {
            DB::transaction(function () use ($validated, $actorUserId): void {
                $internalUser = User::query()->create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'user_type' => 'internal',
                    'b2b_type' => null,
                    'company_id' => null,
                    'status' => 'active',
                    'approved_at' => now(),
                    'approved_by_user_id' => $actorUserId,
                    'created_by_user_id' => $actorUserId,
                    'password' => Hash::make($validated['password']),
                ]);

                $departments = Department::query()
                    ->where('is_active', true)
                    ->whereIn('id', collect($validated['department_ids'])->map(fn ($id) => (int) $id)->unique()->all())
                    ->get(['id', 'slug']);

                foreach ($departments as $department) {
                    $this->rolePermissionService->assignRole($internalUser, 'internal_user_'.$department->slug);
                }

                $internalUser->departments()->syncWithoutDetaching($departments->pluck('id')->all());
            });
        } catch (Throwable $exception) {
            Log::error('Failed to create internal user.', ['email' => $validated['email'] ?? null, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This creates a delegated admin user and assigns the delegated-admin role.
    public function createDelegatedAdminUser(array $validated, int $actorUserId): void
    {
        try {
            DB::transaction(function () use ($validated, $actorUserId): void {
                $delegatedAdminUser = User::query()->create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'user_type' => 'delegated_admin',
                    'b2b_type' => null,
                    'company_id' => null,
                    'status' => 'active',
                    'approved_at' => now(),
                    'approved_by_user_id' => $actorUserId,
                    'created_by_user_id' => $actorUserId,
                    'password' => Hash::make($validated['password']),
                ]);

                $this->rolePermissionService->assignRole($delegatedAdminUser, 'delegated_admin');
                $this->rolePermissionService->detachRole($delegatedAdminUser, 'internal_user');
            });
        } catch (Throwable $exception) {
            Log::error('Failed to create delegated admin user.', ['email' => $validated['email'] ?? null, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This checks whether the target user exists.
    public function userExists(int $userId): bool
    {
        try {
            return User::query()->whereKey($userId)->exists();
        } catch (Throwable $exception) {
            Log::error('Failed to check user existence.', ['user_id' => $userId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This creates or updates a user-level permission override.
    public function setUserPermission(int $userId, int $permissionId, string $grantType, int $grantedByUserId): void
    {
        try {
            UserPermission::query()->updateOrCreate(
                ['user_id' => $userId, 'permission_id' => $permissionId],
                ['grant_type' => $grantType, 'granted_by_user_id' => $grantedByUserId],
            );
        } catch (Throwable $exception) {
            Log::error('Failed to save user permission override.', ['user_id' => $userId, 'permission_id' => $permissionId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This deletes a user-level permission override.
    public function deleteUserPermission(int $overrideId): void
    {
        try {
            UserPermission::query()->whereKey($overrideId)->delete();
        } catch (Throwable $exception) {
            Log::error('Failed to delete user permission override.', ['override_id' => $overrideId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This loads a user or fails when the record does not exist.
    public function findUserOrFail(int $userId): User
    {
        try {
            return User::query()->findOrFail($userId);
        } catch (Throwable $exception) {
            Log::error('Failed to load user.', ['user_id' => $userId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This creates or updates a delegated-admin company scope.
    public function setDelegatedCompanyScope(int $delegatedAdminUserId, int $companyId, int $assignedByUserId): void
    {
        try {
            DelegatedAdminScope::query()->updateOrCreate(
                [
                    'delegated_admin_user_id' => $delegatedAdminUserId,
                    'scope_type' => 'company',
                    'scope_value' => (string) $companyId,
                ],
                ['assigned_by_user_id' => $assignedByUserId],
            );
        } catch (Throwable $exception) {
            Log::error('Failed to save delegated admin scope.', ['delegated_admin_user_id' => $delegatedAdminUserId, 'company_id' => $companyId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This deletes a delegated-admin scope row.
    public function deleteDelegatedScope(int $scopeId): void
    {
        try {
            DelegatedAdminScope::query()->whereKey($scopeId)->delete();
        } catch (Throwable $exception) {
            Log::error('Failed to delete delegated scope.', ['scope_id' => $scopeId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This adds company names to delegated scope rows for simpler view usage.
    protected function mapDelegatedScopes(Collection $scopes): Collection
    {
        try {
            $companyMap = Company::query()
                ->whereIn('id', $scopes->where('scope_type', 'company')->pluck('scope_value')->map(fn ($value) => (int) $value)->all())
                ->pluck('name', 'id');

            return $scopes->map(function (DelegatedAdminScope $scope) use ($companyMap) {
                $scope->delegated_name = $scope->delegatedAdmin?->name;
                $scope->delegated_email = $scope->delegatedAdmin?->email;
                $scope->assigned_by_name = $scope->assignedBy?->name;
                $scope->company_name = $scope->scope_type === 'company'
                    ? $companyMap->get((int) $scope->scope_value)
                    : null;

                return $scope;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to map delegated scopes.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}

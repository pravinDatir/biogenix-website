<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserManagementService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
        protected RolePermissionService $rolePermissionService,
    ) {
    }

    /**
     * @return array{
     *     pendingB2bUsers: \Illuminate\Support\Collection,
     *     departments: \Illuminate\Support\Collection,
     *     permissions: \Illuminate\Support\Collection,
     *     users: \Illuminate\Support\Collection,
     *     delegatedAdmins: \Illuminate\Support\Collection,
     *     companies: \Illuminate\Support\Collection,
     *     userOverrides: \Illuminate\Support\Collection,
     *     delegatedScopes: \Illuminate\Support\Collection
     * }
     */
    public function indexData(User $currentUser): array
    {
        $delegatedScopeCompanyIds = $this->dataVisibilityService->delegatedAdminCompanyScopeIds($currentUser);

        $pendingB2bUsers = DB::table('users')
            ->leftJoin('companies', 'companies.id', '=', 'users.company_id')
            ->where('users.user_type', 'b2b')
            ->where('users.status', 'pending_approval')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.b2b_type',
                'users.created_at',
                'companies.name as company_name',
            )
            ->orderBy('users.created_at')
            ->get();

        $departments = DB::table('departments')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $permissions = DB::table('permissions')
            ->orderBy('slug')
            ->get();

        $users = DB::table('users')
            ->select('id', 'name', 'email', 'user_type', 'status', 'company_id')
            ->orderByDesc('id')
            ->limit(200)
            ->get();

        $delegatedAdmins = DB::table('users as u')
            ->join('role_user as ru', 'ru.user_id', '=', 'u.id')
            ->join('roles as r', 'r.id', '=', 'ru.role_id')
            ->where('r.slug', 'delegated_admin')
            ->select('u.id', 'u.name', 'u.email')
            ->distinct()
            ->orderBy('u.name')
            ->get();

        $companiesQuery = DB::table('companies')
            ->select('id', 'name')
            ->orderBy('name');

        if ($this->rolePermissionService->hasRole($currentUser, 'delegated_admin')
            && ! $this->rolePermissionService->hasRole($currentUser, 'admin')) {
            $companiesQuery->whereIn('id', $delegatedScopeCompanyIds ?: [-1]);
        }

        $companies = $companiesQuery->get();

        $userOverrides = DB::table('user_permissions as up')
            ->join('users as u', 'u.id', '=', 'up.user_id')
            ->join('permissions as p', 'p.id', '=', 'up.permission_id')
            ->leftJoin('users as granted_by', 'granted_by.id', '=', 'up.granted_by_user_id')
            ->select(
                'up.id',
                'u.name as user_name',
                'u.email as user_email',
                'p.slug as permission_slug',
                'up.grant_type',
                'granted_by.name as granted_by_name',
                'up.updated_at',
            )
            ->orderByDesc('up.id')
            ->limit(200)
            ->get();

        $delegatedScopes = DB::table('delegated_admin_scopes as das')
            ->join('users as delegated', 'delegated.id', '=', 'das.delegated_admin_user_id')
            ->leftJoin('companies as c', function ($join): void {
                $join->on('das.scope_value', '=', DB::raw('CAST(c.id AS CHAR)'))
                    ->where('das.scope_type', 'company');
            })
            ->leftJoin('users as assigned_by', 'assigned_by.id', '=', 'das.assigned_by_user_id')
            ->select(
                'das.id',
                'delegated.name as delegated_name',
                'delegated.email as delegated_email',
                'das.scope_type',
                'das.scope_value',
                'c.name as company_name',
                'assigned_by.name as assigned_by_name',
                'das.updated_at',
            )
            ->orderByDesc('das.id')
            ->limit(200)
            ->get();

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
    }

    public function approveB2bUser(int $userId, int $approvedByUserId): int
    {
        return DB::table('users')
            ->where('id', $userId)
            ->where('user_type', 'b2b')
            ->where('status', 'pending_approval')
            ->update([
                'status' => 'active',
                'approved_at' => now(),
                'approved_by_user_id' => $approvedByUserId,
                'updated_at' => now(),
            ]);
    }

    public function rejectB2bUser(int $userId, int $approvedByUserId): int
    {
        return DB::table('users')
            ->where('id', $userId)
            ->where('user_type', 'b2b')
            ->where('status', 'pending_approval')
            ->update([
                'status' => 'blocked',
                'approved_at' => now(),
                'approved_by_user_id' => $approvedByUserId,
                'updated_at' => now(),
            ]);
    }

    /**
     * @param  array{name: string, email: string, password: string, department_ids: array<int, mixed>}  $validated
     */
    public function createInternalUser(array $validated, int $actorUserId): void
    {
        DB::transaction(function () use ($validated, $actorUserId): void {
            $userId = DB::table('users')->insertGetId([
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
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $deptMap = DB::table('departments')
                ->where('is_active', true)
                ->pluck('slug', 'id');

            $internalUser = User::query()->findOrFail($userId);

            $uniqueDepartmentIds = collect($validated['department_ids'])
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            foreach ($uniqueDepartmentIds as $departmentId) {
                $deptSlug = $deptMap->get($departmentId);

                if (! $deptSlug) {
                    continue;
                }

                $this->rolePermissionService->assignRole($internalUser, 'internal_user_'.$deptSlug);
            }

            $departmentRows = collect($validated['department_ids'])
                ->unique()
                ->map(fn ($departmentId) => [
                    'department_id' => (int) $departmentId,
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
                ->values()
                ->all();

            DB::table('department_user')->upsert(
                $departmentRows,
                ['department_id', 'user_id'],
                ['updated_at'],
            );
        });
    }

    /**
     * @param  array{name: string, email: string, password: string}  $validated
     */
    public function createDelegatedAdminUser(array $validated, int $actorUserId): void
    {
        DB::transaction(function () use ($validated, $actorUserId): void {
            $userId = DB::table('users')->insertGetId([
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
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $delegatedAdminUser = User::query()->findOrFail($userId);
            $this->rolePermissionService->assignRole($delegatedAdminUser, 'delegated_admin');
            $this->rolePermissionService->detachRole($delegatedAdminUser, 'internal_user');
        });
    }

    public function userExists(int $userId): bool
    {
        return DB::table('users')->where('id', $userId)->exists();
    }

    public function setUserPermission(int $userId, int $permissionId, string $grantType, int $grantedByUserId): void
    {
        DB::table('user_permissions')->updateOrInsert(
            ['user_id' => $userId, 'permission_id' => $permissionId],
            [
                'grant_type' => $grantType,
                'granted_by_user_id' => $grantedByUserId,
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );
    }

    public function deleteUserPermission(int $overrideId): void
    {
        DB::table('user_permissions')->where('id', $overrideId)->delete();
    }

    public function findUserOrFail(int $userId): User
    {
        return User::query()->findOrFail($userId);
    }

    public function setDelegatedCompanyScope(int $delegatedAdminUserId, int $companyId, int $assignedByUserId): void
    {
        DB::table('delegated_admin_scopes')->updateOrInsert(
            [
                'delegated_admin_user_id' => $delegatedAdminUserId,
                'scope_type' => 'company',
                'scope_value' => (string) $companyId,
            ],
            [
                'assigned_by_user_id' => $assignedByUserId,
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );
    }

    public function deleteDelegatedScope(int $scopeId): void
    {
        DB::table('delegated_admin_scopes')->where('id', $scopeId)->delete();
    }
}

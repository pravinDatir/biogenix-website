<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DataVisibilityService;
use App\Services\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserManagementController extends Controller
{
    public function index(
        Request $request,
        RolePermissionService $rolePermissionService,
        DataVisibilityService $dataVisibilityService,
    ): View {
        $currentUser = $request->user();
        $delegatedScopeCompanyIds = $dataVisibilityService->delegatedAdminCompanyScopeIds($currentUser);

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

        if ($rolePermissionService->hasRole($currentUser, 'delegated_admin')
            && ! $rolePermissionService->hasRole($currentUser, 'admin')) {
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

        return view('admin.users.index', [
            'pendingB2bUsers' => $pendingB2bUsers,
            'departments' => $departments,
            'permissions' => $permissions,
            'users' => $users,
            'delegatedAdmins' => $delegatedAdmins,
            'companies' => $companies,
            'userOverrides' => $userOverrides,
            'delegatedScopes' => $delegatedScopes,
        ]);
    }

    public function approveB2b(
        Request $request,
        int $userId,
        RolePermissionService $rolePermissionService,
    ): RedirectResponse
    {
        if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
            abort(403, 'Only admin can approve B2B registrations.');
        }

        $affected = DB::table('users')
            ->where('id', $userId)
            ->where('user_type', 'b2b')
            ->where('status', 'pending_approval')
            ->update([
                'status' => 'active',
                'approved_at' => now(),
                'approved_by_user_id' => $request->user()->id,
                'updated_at' => now(),
            ]);

        if (! $affected) {
            return redirect()->route('admin.users.index')
                ->with('status', 'No matching pending B2B request found.');
        }

        return redirect()->route('admin.users.index')
            ->with('status', 'B2B user approved successfully.');
    }

    public function rejectB2b(
        Request $request,
        int $userId,
        RolePermissionService $rolePermissionService,
    ): RedirectResponse
    {
        if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
            abort(403, 'Only admin can reject B2B registrations.');
        }

        $affected = DB::table('users')
            ->where('id', $userId)
            ->where('user_type', 'b2b')
            ->where('status', 'pending_approval')
            ->update([
                'status' => 'blocked',
                'approved_at' => now(),
                'approved_by_user_id' => $request->user()->id,
                'updated_at' => now(),
            ]);

        if (! $affected) {
            return redirect()->route('admin.users.index')
                ->with('status', 'No matching pending B2B request found.');
        }

        return redirect()->route('admin.users.index')
            ->with('status', 'B2B request rejected and user blocked.');
    }

    public function createInternal(
        Request $request,
        RolePermissionService $rolePermissionService,
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'department_ids' => ['required', 'array', 'min:1'],
            'department_ids.*' => ['integer', 'exists:departments,id'],
        ]);

        DB::transaction(function () use ($validated, $request, $rolePermissionService): void {
            $userId = DB::table('users')->insertGetId([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'user_type' => 'internal',
                'b2b_type' => null,
                'company_id' => null,
                'status' => 'active',
                'approved_at' => now(),
                'approved_by_user_id' => $request->user()->id,
                'created_by_user_id' => $request->user()->id,
                'password' => Hash::make($validated['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $internalUser = User::query()->findOrFail($userId);
            $rolePermissionService->assignRole($internalUser, 'internal_user');

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

        return redirect()->route('admin.users.index')
            ->with('status', 'Internal user created and assigned to departments.');
    }

    public function createDelegatedAdmin(
        Request $request,
        RolePermissionService $rolePermissionService,
    ): RedirectResponse {
        if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
            abort(403, 'Only admin can create delegated admin users.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($validated, $request, $rolePermissionService): void {
            $userId = DB::table('users')->insertGetId([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'user_type' => 'delegated_admin',
                'b2b_type' => null,
                'company_id' => null,
                'status' => 'active',
                'approved_at' => now(),
                'approved_by_user_id' => $request->user()->id,
                'created_by_user_id' => $request->user()->id,
                'password' => Hash::make($validated['password']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $delegatedAdminUser = User::query()->findOrFail($userId);
            $rolePermissionService->assignRole($delegatedAdminUser, 'delegated_admin');
            $rolePermissionService->detachRole($delegatedAdminUser, 'internal_user');
        });

        return redirect()->route('admin.users.index')
            ->with('status', 'Delegated admin created successfully.');
    }

    public function setUserPermission(Request $request, int $userId): RedirectResponse
    {
        if ($userId === 0) {
            $userId = (int) $request->input('override_user_id', 0);
        }

        if (! DB::table('users')->where('id', $userId)->exists()) {
            return redirect()->route('admin.users.index')
                ->with('status', 'Selected user does not exist.');
        }

        $validated = $request->validate([
            'permission_id' => ['required', 'integer', 'exists:permissions,id'],
            'grant_type' => ['required', Rule::in(['allow', 'deny'])],
        ]);

        DB::table('user_permissions')->updateOrInsert(
            ['user_id' => $userId, 'permission_id' => (int) $validated['permission_id']],
            [
                'grant_type' => $validated['grant_type'],
                'granted_by_user_id' => $request->user()->id,
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );

        return redirect()->route('admin.users.index')
            ->with('status', 'User-level permission override saved.');
    }

    public function deleteUserPermission(int $overrideId): RedirectResponse
    {
        DB::table('user_permissions')->where('id', $overrideId)->delete();

        return redirect()->route('admin.users.index')
            ->with('status', 'User-level permission override removed.');
    }

    public function setDelegatedCompanyScope(
        Request $request,
        int $userId,
        RolePermissionService $rolePermissionService,
    ): RedirectResponse {
        if ($userId === 0) {
            $userId = (int) $request->input('scope_user_id', 0);
        }

        if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
            abort(403, 'Only admin can set delegated scopes.');
        }

        $validated = $request->validate([
            'company_id' => ['required', 'integer', 'exists:companies,id'],
        ]);

        $targetUser = User::query()->findOrFail($userId);

        if (! $rolePermissionService->hasRole($targetUser, 'delegated_admin')) {
            return redirect()->route('admin.users.index')
                ->with('status', 'Target user is not a delegated admin.');
        }

        DB::table('delegated_admin_scopes')->updateOrInsert(
            [
                'delegated_admin_user_id' => $targetUser->id,
                'scope_type' => 'company',
                'scope_value' => (string) ((int) $validated['company_id']),
            ],
            [
                'assigned_by_user_id' => $request->user()->id,
                'updated_at' => now(),
                'created_at' => now(),
            ],
        );

        return redirect()->route('admin.users.index')
            ->with('status', 'Delegated admin company scope saved.');
    }

    public function deleteDelegatedScope(
        Request $request,
        int $scopeId,
        RolePermissionService $rolePermissionService,
    ): RedirectResponse
    {
        if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
            abort(403, 'Only admin can remove delegated scopes.');
        }

        DB::table('delegated_admin_scopes')->where('id', $scopeId)->delete();

        return redirect()->route('admin.users.index')
            ->with('status', 'Delegated admin scope removed.');
    }
}

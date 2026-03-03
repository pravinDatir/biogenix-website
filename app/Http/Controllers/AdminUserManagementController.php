<?php

namespace App\Http\Controllers;

use App\Services\AdminUserManagementService;
use App\Services\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserManagementController extends Controller
{
    public function index(
        Request $request,
        AdminUserManagementService $adminUserManagementService,
    ): View {
        return view('admin.users.index', $adminUserManagementService->indexData($request->user()));
    }

    public function approveB2b(
        Request $request,
        int $userId,
        AdminUserManagementService $adminUserManagementService,
        RolePermissionService $rolePermissionService,
    ): RedirectResponse
    {
        if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
            abort(403, 'Only admin can approve B2B registrations.');
        }

        $affected = $adminUserManagementService->approveB2bUser($userId, $request->user()->id);

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
        AdminUserManagementService $adminUserManagementService,
        RolePermissionService $rolePermissionService,
    ): RedirectResponse
    {
        if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
            abort(403, 'Only admin can reject B2B registrations.');
        }

        $affected = $adminUserManagementService->rejectB2bUser($userId, $request->user()->id);

        if (! $affected) {
            return redirect()->route('admin.users.index')
                ->with('status', 'No matching pending B2B request found.');
        }

        return redirect()->route('admin.users.index')
            ->with('status', 'B2B request rejected and user blocked.');
    }

    public function createInternal(
        Request $request,
        AdminUserManagementService $adminUserManagementService,
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'department_ids' => ['required', 'array', 'min:1'],
            'department_ids.*' => ['integer', 'exists:departments,id'],
        ]);

        $adminUserManagementService->createInternalUser($validated, $request->user()->id);

        return redirect()->route('admin.users.index')
            ->with('status', 'Internal user created and assigned to departments.');
    }

    public function createDelegatedAdmin(
        Request $request,
        AdminUserManagementService $adminUserManagementService,
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

        $adminUserManagementService->createDelegatedAdminUser($validated, $request->user()->id);

        return redirect()->route('admin.users.index')
            ->with('status', 'Delegated admin created successfully.');
    }

    public function setUserPermission(
        Request $request,
        int $userId,
        AdminUserManagementService $adminUserManagementService,
    ): RedirectResponse
    {
        if ($userId === 0) {
            $userId = (int) $request->input('override_user_id', 0);
        }

        if (! $adminUserManagementService->userExists($userId)) {
            return redirect()->route('admin.users.index')
                ->with('status', 'Selected user does not exist.');
        }

        $validated = $request->validate([
            'permission_id' => ['required', 'integer', 'exists:permissions,id'],
            'grant_type' => ['required', Rule::in(['allow', 'deny'])],
        ]);

        $adminUserManagementService->setUserPermission(
            $userId,
            (int) $validated['permission_id'],
            $validated['grant_type'],
            $request->user()->id,
        );

        return redirect()->route('admin.users.index')
            ->with('status', 'User-level permission override saved.');
    }

    public function deleteUserPermission(
        int $overrideId,
        AdminUserManagementService $adminUserManagementService,
    ): RedirectResponse
    {
        $adminUserManagementService->deleteUserPermission($overrideId);

        return redirect()->route('admin.users.index')
            ->with('status', 'User-level permission override removed.');
    }

    public function setDelegatedCompanyScope(
        Request $request,
        int $userId,
        AdminUserManagementService $adminUserManagementService,
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

        $targetUser = $adminUserManagementService->findUserOrFail($userId);

        if (! $rolePermissionService->hasRole($targetUser, 'delegated_admin')) {
            return redirect()->route('admin.users.index')
                ->with('status', 'Target user is not a delegated admin.');
        }

        $adminUserManagementService->setDelegatedCompanyScope(
            $targetUser->id,
            (int) $validated['company_id'],
            $request->user()->id,
        );

        return redirect()->route('admin.users.index')
            ->with('status', 'Delegated admin company scope saved.');
    }

    public function deleteDelegatedScope(
        Request $request,
        int $scopeId,
        AdminUserManagementService $adminUserManagementService,
        RolePermissionService $rolePermissionService,
    ): RedirectResponse
    {
        if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
            abort(403, 'Only admin can remove delegated scopes.');
        }

        $adminUserManagementService->deleteDelegatedScope($scopeId);

        return redirect()->route('admin.users.index')
            ->with('status', 'Delegated admin scope removed.');
    }
}

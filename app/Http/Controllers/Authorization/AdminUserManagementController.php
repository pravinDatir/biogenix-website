<?php

namespace App\Http\Controllers\Authorization;

use App\Http\Controllers\Controller;
use App\Services\Authorization\AdminUserManagementService;
use App\Services\Authorization\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class AdminUserManagementController extends Controller
{
    // This renders the admin user management page.
    public function index( Request $request, AdminUserManagementService $adminUserManagementService): View {
        try {
            // Step 1: load all data needed on the admin users page.
            return view('admin.users.index', $adminUserManagementService->indexData($request->user()));
        } catch (Throwable $exception) {
            Log::error('Failed to load admin user management page.', ['error' => $exception->getMessage()]);
            return $this->viewWithError('admin.users.index',[], $exception, 'Unable to load user management.');
        }
    }

    // This approves a pending B2B user.
    public function approveB2b( Request $request, int $userId, AdminUserManagementService $adminUserManagementService,
        RolePermissionService $rolePermissionService, ): RedirectResponse
    {
        try {
            // Step 1: allow only admins to approve B2B users.
            if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
                abort(403, 'Only admin can approve B2B registrations.');
            }

            // Step 2: approve the selected pending B2B user.
            $affected = $adminUserManagementService->approveB2bUser($userId, $request->user()->id);

            if (! $affected) {
                return redirect()->route('admin.users.index')
                    ->with('status', 'No matching pending B2B request found.');
            }

            return redirect()->route('admin.users.index')
                ->with('status', 'B2B user approved successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to approve B2B user.', ['user_id' => $userId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to approve B2B user.');
        }
    }

    // This rejects a pending B2B user.
    public function rejectB2b(  Request $request, int $userId,  AdminUserManagementService $adminUserManagementService,  RolePermissionService $rolePermissionService): RedirectResponse
    {
        try {
            // Step 1: allow only admins to reject B2B users.
            if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
                abort(403, 'Only admin can reject B2B registrations.');
            }

            // Step 2: reject the selected pending B2B user.
            $affected = $adminUserManagementService->rejectB2bUser($userId, $request->user()->id);

            if (! $affected) {
                return redirect()->route('admin.users.index')
                    ->with('status', 'No matching pending B2B request found.');
            }

            return redirect()->route('admin.users.index')
                ->with('status', 'B2B request rejected and user blocked.');
        } catch (Throwable $exception) {
            Log::error('Failed to reject B2B user.', ['user_id' => $userId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to reject B2B user.');
        }
    }

    // This creates a new internal user.
    public function createInternal( Request $request, AdminUserManagementService $adminUserManagementService ): RedirectResponse {
        try {
            // Step 1: validate the internal user form.
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'department_ids' => ['required', 'array', 'min:1'],
                'department_ids.*' => ['integer', 'exists:departments,id'],
            ]);

            // Step 2: create the internal user and assign departments.
            $adminUserManagementService->createInternalUser($validated, $request->user()->id);

            return redirect()->route('admin.users.index')
                ->with('status', 'Internal user created and assigned to departments.');
        } catch (Throwable $exception) {
            Log::error('Failed to create internal user.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to create internal user.');
        }
    }

    // This creates a delegated admin user.
    public function createDelegatedAdmin( Request $request,  AdminUserManagementService $adminUserManagementService,  RolePermissionService $rolePermissionService, ): RedirectResponse {
        try {
            // Step 1: allow only admins to create delegated admins.
            if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
                abort(403, 'Only admin can create delegated admin users.');
            }

            // Step 2: validate the delegated admin form.
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            // Step 3: create the delegated admin account.
            $adminUserManagementService->createDelegatedAdminUser($validated, $request->user()->id);

            return redirect()->route('admin.users.index')
                ->with('status', 'Delegated admin created successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to create delegated admin.', ['error' => $exception->getMessage()]);
            return $this->redirectBackWithError($exception, 'Unable to create delegated admin.');
        }
    }

    // This creates or updates a user-level permission override.
    public function setUserPermission( Request $request, int $userId,  AdminUserManagementService $adminUserManagementService, ): RedirectResponse
    {
        try {
            // Step 1: support both route id and form id.
            if ($userId === 0) {
                $userId = (int) $request->input('override_user_id', 0);
            }

            if (! $adminUserManagementService->userExists($userId)) {
                return redirect()->route('admin.users.index')
                    ->with('status', 'Selected user does not exist.');
            }

            // Step 2: validate the permission override form.
            $validated = $request->validate([
                'permission_id' => ['required', 'integer', 'exists:permissions,id'],
                'grant_type' => ['required', Rule::in(['allow', 'deny'])],
            ]);

            // Step 3: save the permission override.
            $adminUserManagementService->setUserPermission(
                $userId,
                (int) $validated['permission_id'],
                $validated['grant_type'],
                $request->user()->id,
            );

            return redirect()->route('admin.users.index')
                ->with('status', 'User-level permission override saved.');
        } catch (Throwable $exception) {
            Log::error('Failed to save user permission override.', ['user_id' => $userId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to save permission override.');
        }
    }

    // This removes one user-level permission override.
    public function deleteUserPermission( int $overrideId,  AdminUserManagementService $adminUserManagementService, ): RedirectResponse
    {
        try {
            // Step 1: delete the selected permission override.
            $adminUserManagementService->deleteUserPermission($overrideId);

            return redirect()->route('admin.users.index')
                ->with('status', 'User-level permission override removed.');
        } catch (Throwable $exception) {
            Log::error('Failed to delete user permission override.', ['override_id' => $overrideId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to delete permission override.');
        }
    }

    // This assigns one company scope to a delegated admin.
    public function setDelegatedCompanyScope(  Request $request,  int $userId,   AdminUserManagementService $adminUserManagementService,  RolePermissionService $rolePermissionService,  ): RedirectResponse {
        try {
            // Step 1: support both route id and form id.
            if ($userId === 0) {
                $userId = (int) $request->input('scope_user_id', 0);
            }

            if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
                abort(403, 'Only admin can set delegated scopes.');
            }

            // Step 2: validate the scope form.
            $validated = $request->validate([
                'company_id' => ['required', 'integer', 'exists:companies,id'],
            ]);

            $targetUser = $adminUserManagementService->findUserOrFail($userId);

            if (! $rolePermissionService->hasRole($targetUser, 'delegated_admin')) {
                return redirect()->route('admin.users.index')
                    ->with('status', 'Target user is not a delegated admin.');
            }

            // Step 3: save the delegated admin company scope.
            $adminUserManagementService->setDelegatedCompanyScope(
                $targetUser->id,
                (int) $validated['company_id'],
                $request->user()->id,
            );

            return redirect()->route('admin.users.index')
                ->with('status', 'Delegated admin company scope saved.');
        } catch (Throwable $exception) {
            Log::error('Failed to save delegated company scope.', ['user_id' => $userId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to save delegated scope.');
        }
    }

    // This removes one delegated admin scope row.
    public function deleteDelegatedScope( Request $request, int $scopeId, AdminUserManagementService $adminUserManagementService, RolePermissionService $rolePermissionService,): RedirectResponse
    {
        try {
            // Step 1: allow only admins to remove delegated scopes.
            if (! $rolePermissionService->hasRole($request->user(), 'admin')) {
                abort(403, 'Only admin can remove delegated scopes.');
            }

            // Step 2: delete the selected delegated scope row.
            $adminUserManagementService->deleteDelegatedScope($scopeId);

            return redirect()->route('admin.users.index')
                ->with('status', 'Delegated admin scope removed.');
        } catch (Throwable $exception) {
            Log::error('Failed to delete delegated scope.', ['scope_id' => $scopeId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to delete delegated scope.');
        }
    }
}

<?php

namespace App\Http\Controllers\Authorization;

use App\Http\Controllers\Controller;
use App\Services\Authorization\RoleAndPermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class RoleAndPermissionController extends Controller
{
    // This renders the role management page and optional edit state.
    public function getRole(Request $request, RoleAndPermissionService $roleAndPermissionService, ?int $roleId = null): View
    {
        try {
            // Step 1: load the list page and optional editing role.
            return view('admin.roles.index', $roleAndPermissionService->rolePageData(
                $roleId,
                $request->integer('edit_permission_id') ?: null,
            ));
        } catch (Throwable $exception) {
            Log::error('Failed to load role page.', ['role_id' => $roleId, 'error' => $exception->getMessage()]);

            return $this->viewWithError('admin.roles.index', [
                'roles' => collect(),
                'permissions' => collect(),
                'editingRole' => null,
                'editingPermission' => null,
                'editingRolePermissionIds' => [],
            ], $exception, 'Unable to load role page.');
        }
    }

    // This validates and stores a new role.
    public function addRole(Request $request, RoleAndPermissionService $roleAndPermissionService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted role fields.
            $validated = $this->validateRolePayload($request);

            // Step 2: create the role and redirect back to the list page.
            $roleAndPermissionService->addRole($validated);

            return redirect()->route('admin.roles.index')
                ->with('status', 'Role added successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to add role.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to add role.');
        }
    }

    // This validates and updates one role.
    public function updateRole(int $roleId, Request $request, RoleAndPermissionService $roleAndPermissionService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted role fields.
            $validated = $this->validateRolePayload($request);

            // Step 2: update the selected role and stay in edit mode.
            $roleAndPermissionService->updateRole($roleId, $validated);

            return redirect()->route('admin.roles.show', $roleId)
                ->with('status', 'Role updated successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to update role.', ['role_id' => $roleId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to update role.');
        }
    }

    // This deletes one role row.
    public function deleteRole(int $roleId, RoleAndPermissionService $roleAndPermissionService): RedirectResponse
    {
        try {
            // Step 1: delete the selected role and return to the list page.
            $roleAndPermissionService->deleteRole($roleId);

            return redirect()->route('admin.roles.index')
                ->with('status', 'Role deleted successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to delete role.', ['role_id' => $roleId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to delete role.');
        }
    }

    // This validates and stores a new permission.
    public function createPermission(Request $request, RoleAndPermissionService $roleAndPermissionService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted permission fields.
            $validated = $this->validatePermissionPayload($request);

            // Step 2: create the permission and return to the list page.
            $roleAndPermissionService->createPermission($validated);

            return redirect()->route('admin.roles.index')
                ->with('status', 'Permission added successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to create permission.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to add permission.');
        }
    }

    // This validates and updates one permission.
    public function updatePermission(int $permissionId, Request $request, RoleAndPermissionService $roleAndPermissionService): RedirectResponse
    {
        try {
            // Step 1: validate the submitted permission fields.
            $validated = $this->validatePermissionPayload($request);

            // Step 2: update the selected permission and stay in permission edit mode.
            $roleAndPermissionService->updatePermission($permissionId, $validated);

            return redirect()->route('admin.roles.index', ['edit_permission_id' => $permissionId])
                ->with('status', 'Permission updated successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to update permission.', ['permission_id' => $permissionId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to update permission.');
        }
    }

    // This deletes one permission row.
    public function deletePermission(int $permissionId, RoleAndPermissionService $roleAndPermissionService): RedirectResponse
    {
        try {
            // Step 1: delete the selected permission and return to the list page.
            $roleAndPermissionService->deletePermission($permissionId);

            return redirect()->route('admin.roles.index')
                ->with('status', 'Permission deleted successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to delete permission.', ['permission_id' => $permissionId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to delete permission.');
        }
    }

    // This saves all checked permissions for the selected role.
    public function upsertPermissionsForRole(int $roleId, Request $request, RoleAndPermissionService $roleAndPermissionService): RedirectResponse
    {
        try {
            // Step 1: validate the checkbox array from the role-permission form.
            $validated = $request->validate([
                'permission_ids' => ['nullable', 'array'],
                'permission_ids.*' => ['integer', 'exists:permissions,id'],
            ]);

            // Step 2: sync checked permissions for the selected role.
            $roleAndPermissionService->upsertPermissionsForRole(
                $roleId,
                $validated['permission_ids'] ?? [],
            );

            return redirect()->route('admin.roles.show', $roleId)
                ->with('status', 'Role permissions updated successfully.');
        } catch (Throwable $exception) {
            Log::error('Failed to sync role permissions.', ['role_id' => $roleId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to update role permissions.');
        }
    }

    // This validates the minimal role form fields.
    protected function validateRolePayload(Request $request): array
    {
        try {
            return $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['nullable', 'string', 'max:255'],
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to validate role payload.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This validates the minimal permission form fields.
    protected function validatePermissionPayload(Request $request): array
    {
        try {
            return $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['nullable', 'string', 'max:255'],
            ]);
        } catch (Throwable $exception) {
            Log::error('Failed to validate permission payload.', ['error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}

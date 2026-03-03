<?php

namespace App\Http\Controllers;

use App\Services\ImpersonationService;
use App\Services\DataVisibilityService;
use App\Services\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function start(
        Request $request,
        int $targetUserId,
        ImpersonationService $impersonationService,
        RolePermissionService $rolePermissionService,
        DataVisibilityService $dataVisibilityService,
    ): RedirectResponse {
        $impersonator = $request->user();

        if (! $rolePermissionService->hasPermission($impersonator, 'users.impersonate')) {
            abort(403, 'You are not allowed to impersonate users.');
        }

        if ($request->session()->has('impersonation.impersonator_id')) {
            return redirect()->back()->with('status', 'Stop current impersonation before starting another.');
        }

        if ($impersonator->id === $targetUserId) {
            return redirect()->back()->with('status', 'Cannot impersonate your own account.');
        }

        $targetUser = $impersonationService->findTargetUserOrFail($targetUserId);

        if ($targetUser->status !== 'active') {
            return redirect()->back()->with('status', 'Cannot impersonate an inactive user.');
        }

        if ($rolePermissionService->hasRole($targetUser, 'admin')
            || $rolePermissionService->hasRole($targetUser, 'delegated_admin')) {
            return redirect()->back()->with('status', 'Impersonation of admin/delegated admin users is not allowed.');
        }

        if ($rolePermissionService->hasRole($impersonator, 'delegated_admin')
            && ! $rolePermissionService->hasRole($impersonator, 'admin')) {
            if (! $dataVisibilityService->canAccessCompanyData($impersonator, $targetUser->company_id)) {
                abort(403, 'Target user is outside delegated admin scope.');
            }
        }

        $auditId = $impersonationService->startAudit(
            $impersonator->id,
            $targetUser->id,
            trim((string) $request->input('reason')) ?: null,
            $request->ip(),
            $request->userAgent(),
        );

        $request->session()->put('impersonation.impersonator_id', $impersonator->id);
        $request->session()->put('impersonation.audit_id', $auditId);

        Auth::loginUsingId($targetUser->id);

        return redirect()->route('dashboard')
            ->with('status', 'Impersonation started.');
    }

    public function stop(Request $request, ImpersonationService $impersonationService): RedirectResponse
    {
        $impersonatorId = $request->session()->get('impersonation.impersonator_id');
        $auditId = $request->session()->get('impersonation.audit_id');

        if (! $impersonatorId) {
            return redirect()->route('dashboard');
        }

        if ($auditId) {
            $impersonationService->closeAudit((int) $auditId);
        }

        $request->session()->forget('impersonation');

        Auth::loginUsingId((int) $impersonatorId);

        return redirect()->route('admin.users.index')
            ->with('status', 'Returned to your original account. Impersonation audit closed.');
    }
}

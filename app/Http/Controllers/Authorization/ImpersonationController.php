<?php

namespace App\Http\Controllers\Authorization;

use App\Http\Controllers\Controller;
use App\Services\Authorization\ImpersonationService;
use App\Services\Authorization\DataVisibilityService;
use App\Services\Authorization\RolePermissionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImpersonationController extends Controller
{
    // This starts impersonation after access and scope checks pass.
    public function start(
        Request $request,
        int $targetUserId,
        ImpersonationService $impersonationService,
        RolePermissionService $rolePermissionService,
        DataVisibilityService $dataVisibilityService,
    ): RedirectResponse {
        try {
            // Step 1: load the acting user and validate impersonation access.
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

            // Step 2: delegated admins can only impersonate users inside their allowed scope.
            if ($rolePermissionService->hasRole($impersonator, 'delegated_admin')
                && ! $rolePermissionService->hasRole($impersonator, 'admin')) {
                if (! $dataVisibilityService->canAccessCompanyData($impersonator, $targetUser->company_id)) {
                    abort(403, 'Target user is outside delegated admin scope.');
                }
            }

            // Step 3: start the audit record and switch the session user.
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
        } catch (Throwable $exception) {
            Log::error('Failed to start impersonation.', ['target_user_id' => $targetUserId, 'error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to start impersonation.');
        }
    }

    // This stops impersonation and restores the original account.
    public function stop(Request $request, ImpersonationService $impersonationService): RedirectResponse
    {
        try {
            // Step 1: read the stored impersonation session data.
            $impersonatorId = $request->session()->get('impersonation.impersonator_id');
            $auditId = $request->session()->get('impersonation.audit_id');

            if (! $impersonatorId) {
                return redirect()->route('dashboard');
            }

            // Step 2: close the audit and restore the original user.
            if ($auditId) {
                $impersonationService->closeAudit((int) $auditId);
            }

            $request->session()->forget('impersonation');

            Auth::loginUsingId((int) $impersonatorId);

            return redirect()->route('admin.users.index')
                ->with('status', 'Returned to your original account. Impersonation audit closed.');
        } catch (Throwable $exception) {
            Log::error('Failed to stop impersonation.', ['error' => $exception->getMessage()]);

            return $this->redirectBackWithError($exception, 'Unable to stop impersonation.');
        }
    }
}

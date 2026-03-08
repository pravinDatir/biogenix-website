<?php

namespace App\Services\Authorization;

use App\Models\Authorization\ImpersonationAudit;
use App\Models\Authorization\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class ImpersonationService
{
    // This loads the target user for an impersonation request.
    public function findTargetUserOrFail(int $targetUserId): User
    {
        try {
            return User::query()->findOrFail($targetUserId);
        } catch (Throwable $exception) {
            Log::error('Failed to load impersonation target.', ['target_user_id' => $targetUserId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This creates an audit row when impersonation starts.
    public function startAudit(
        int $impersonatorUserId,
        int $impersonatedUserId,
        ?string $reason,
        ?string $ipAddress,
        ?string $userAgent,
    ): int {
        try {
            $audit = ImpersonationAudit::query()->create([
                'impersonator_user_id' => $impersonatorUserId,
                'impersonated_user_id' => $impersonatedUserId,
                'reason' => $reason,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'started_at' => now(),
            ]);

            return (int) $audit->id;
        } catch (Throwable $exception) {
            Log::error('Failed to start impersonation audit.', ['impersonator_user_id' => $impersonatorUserId, 'impersonated_user_id' => $impersonatedUserId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This closes the audit row when impersonation stops.
    public function closeAudit(int $auditId): void
    {
        try {
            ImpersonationAudit::query()
                ->whereKey($auditId)
                ->whereNull('ended_at')
                ->update(['ended_at' => now()]);
        } catch (Throwable $exception) {
            Log::error('Failed to close impersonation audit.', ['audit_id' => $auditId, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}

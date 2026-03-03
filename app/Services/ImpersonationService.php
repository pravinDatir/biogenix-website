<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ImpersonationService
{
    public function findTargetUserOrFail(int $targetUserId): User
    {
        return User::query()->findOrFail($targetUserId);
    }

    public function startAudit(
        int $impersonatorUserId,
        int $impersonatedUserId,
        ?string $reason,
        ?string $ipAddress,
        ?string $userAgent,
    ): int {
        return DB::table('impersonation_audits')->insertGetId([
            'impersonator_user_id' => $impersonatorUserId,
            'impersonated_user_id' => $impersonatedUserId,
            'reason' => $reason,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'started_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function closeAudit(int $auditId): void
    {
        DB::table('impersonation_audits')
            ->where('id', $auditId)
            ->whereNull('ended_at')
            ->update([
                'ended_at' => now(),
                'updated_at' => now(),
            ]);
    }
}

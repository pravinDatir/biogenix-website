<?php

namespace App\Services;

use App\Models\Authorization\User;
use App\Services\Authorization\DataVisibilityService;
use App\Services\Authorization\RolePermissionService;
use Illuminate\Support\Facades\Log;
use Throwable;

class DashboardService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
        protected RolePermissionService $rolePermissionService,
    ) {
    }

    // This prepares dashboard counts and access metadata for the logged-in user.
    public function dashboardData(User $user): array
    {
        try {
            return [
                'user' => $user,
                'roleSlugs' => $this->rolePermissionService->roleSlugsForUser($user),
                'permissions' => $this->rolePermissionService->permissionSlugsForUser($user),
                'departments' => $this->departmentsForUser($user),
                'visibleProductsCount' => $this->dataVisibilityService->visibleProductQuery($user)->count(),
                'visiblePiCount' => $this->dataVisibilityService->visibleProformaQuery($user)->count(),
            ];
        } catch (Throwable $exception) {
            Log::error('Failed to build dashboard data.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }

    // This returns department names assigned to the user.
    protected function departmentsForUser(User $user): array
    {
        try {
            return $user->departments()
                ->orderBy('departments.name')
                ->pluck('departments.name')
                ->all();
        } catch (Throwable $exception) {
            Log::error('Failed to load dashboard departments.', ['user_id' => $user->id, 'error' => $exception->getMessage()]);
            throw $exception;
        }
    }
}

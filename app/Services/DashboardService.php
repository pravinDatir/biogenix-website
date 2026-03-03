<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function __construct(
        protected DataVisibilityService $dataVisibilityService,
        protected RolePermissionService $rolePermissionService,
    ) {
    }

    /**
     * @return array{
     *     user: User,
     *     roleSlugs: array<int, string>,
     *     permissions: array<int, string>,
     *     departments: array<int, string>,
     *     visibleProductsCount: int,
     *     visiblePiCount: int
     * }
     */
    public function dashboardData(User $user): array
    {
        return [
            'user' => $user,
            'roleSlugs' => $this->rolePermissionService->roleSlugsForUser($user),
            'permissions' => $this->rolePermissionService->permissionSlugsForUser($user),
            'departments' => $this->departmentsForUser($user),
            'visibleProductsCount' => $this->dataVisibilityService->visibleProductQuery($user)->count(),
            'visiblePiCount' => $this->dataVisibilityService->visibleProformaQuery($user)->count(),
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function departmentsForUser(User $user): array
    {
        return DB::table('departments')
            ->join('department_user', 'department_user.department_id', '=', 'departments.id')
            ->where('department_user.user_id', $user->id)
            ->orderBy('departments.name')
            ->pluck('departments.name')
            ->all();
    }
}

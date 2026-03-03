<?php

namespace App\Services;

use App\Models\User;

class HomeService
{
    public function __construct(
        protected RolePermissionService $rolePermissionService,
    ) {
    }

    /**
     * @return array{roleSlugs: array<int, string>}
     */
    public function viewData(?User $user): array
    {
        return [
            'roleSlugs' => $this->rolePermissionService->roleSlugsForUser($user),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\DataVisibilityService;
use App\Services\RolePermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        DataVisibilityService $dataVisibilityService,
        RolePermissionService $rolePermissionService,
    ): View {
        $user = $request->user();
        $roleSlugs = $rolePermissionService->roleSlugsForUser($user);
        $permissions = $rolePermissionService->permissionSlugsForUser($user);

        $departments = DB::table('departments')
            ->join('department_user', 'department_user.department_id', '=', 'departments.id')
            ->where('department_user.user_id', $user->id)
            ->orderBy('departments.name')
            ->pluck('departments.name')
            ->all();

        return view('dashboard', [
            'user' => $user,
            'roleSlugs' => $roleSlugs,
            'permissions' => $permissions,
            'departments' => $departments,
            'visibleProductsCount' => $dataVisibilityService->visibleProductQuery($user)->count(),
            'visiblePiCount' => $dataVisibilityService->visibleProformaQuery($user)->count(),
        ]);
    }
}

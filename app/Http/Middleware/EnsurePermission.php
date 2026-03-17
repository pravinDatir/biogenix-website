<?php

namespace App\Http\Middleware;

use App\Services\Authorization\RolePermissionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    // This checks if the authenticated user has the required permission to access a route.
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        if (! app(RolePermissionService::class)->hasPermission($user, $permission)) {
            abort(403, 'You do not have permission for this action.');
        }

        return $next($request);
    }
}

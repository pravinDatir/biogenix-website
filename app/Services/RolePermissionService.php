<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolePermissionService
{
    public function roleSlugsForUser(?User $user): array
    {
        if (! $user) {
            return ['guest'];
        }

        $slugs = DB::table('roles')
            ->join('role_user', 'role_user.role_id', '=', 'roles.id')
            ->where('role_user.user_id', $user->id)
            ->pluck('roles.slug')
            ->all();

        if (empty($slugs)) {
            $this->assignDefaultRole($user);

            return $this->roleSlugsForUser($user);
        }

        return $slugs;
    }

    public function hasRole(?User $user, string $roleSlug): bool
    {
        return in_array($roleSlug, $this->roleSlugsForUser($user), true);
    }

    public function hasPermission(?User $user, string $permissionSlug): bool
    {
        if (! $user) {
            return false;
        }

        $userOverride = DB::table('user_permissions as up')
            ->join('permissions as p', 'p.id', '=', 'up.permission_id')
            ->where('up.user_id', $user->id)
            ->where('p.slug', $permissionSlug)
            ->select('up.grant_type')
            ->first();

        if ($userOverride && $userOverride->grant_type === 'deny') {
            return false;
        }

        if ($userOverride && $userOverride->grant_type === 'allow') {
            return true;
        }

        $roleSlugs = $this->roleSlugsForUser($user);

        if (in_array('admin', $roleSlugs, true)) {
            return true;
        }

        return DB::table('permissions as p')
            ->join('permission_role', 'permission_role.permission_id', '=', 'p.id')
            ->join('roles', 'roles.id', '=', 'permission_role.role_id')
            ->whereIn('roles.slug', $roleSlugs)
            ->where('p.slug', $permissionSlug)
            ->exists();
    }
    // This method returns a list of permission slugs for a user after combining:
    // permissions from the user’s roles
    // if user is admin then all permissions are granted
    // user-specific overrides
    public function permissionSlugsForUser(?User $user): array
    {
        if (! $user) {
            return [];
        }

        // Get the user’s role slugs
        $roleSlugs = $this->roleSlugsForUser($user);

        // Get permissions coming from roles
        $fromRoles = DB::table('permissions')
            ->join('permission_role', 'permission_role.permission_id', '=', 'permissions.id')
            ->join('roles', 'roles.id', '=', 'permission_role.role_id')
            ->whereIn('roles.slug', $roleSlugs)
            ->pluck('permissions.slug')
            ->all();

        // Get user-specific permission overrides
        $overrides = DB::table('user_permissions as up')
            ->join('permissions as p', 'p.id', '=', 'up.permission_id')
            ->where('up.user_id', $user->id)
            ->pluck('up.grant_type', 'p.slug');
        
        // Convert role permissions into a lookup map
        $permissions = array_fill_keys($fromRoles, true);

        // If user has admin role, grant all permissions
        if (in_array('admin', $roleSlugs, true)) {
            foreach (DB::table('permissions')->pluck('slug')->all() as $permissionSlug) {
                $permissions[$permissionSlug] = true;
            }
        }

        // Apply user-specific overrides
        foreach ($overrides as $permissionSlug => $grantType) {
            if ($grantType === 'deny') {
                unset($permissions[$permissionSlug]);
                continue;
            }

            $permissions[$permissionSlug] = true;
        }

        // Convert back to a array
        $result = array_keys($permissions);
        sort($result);

        return $result;
    }

    public function assignDefaultRole(User $user): void
    {
        $roleSlug = match ($user->user_type) {
            'b2b' => 'b2b_user',
            'internal' => 'internal_user',
            'admin' => 'admin',
            'delegated_admin' => 'delegated_admin',
            default => 'b2c_customer',
        };

        $this->assignRole($user, $roleSlug);
    }

    public function assignRole(User $user, string $roleSlug): void
    {
        $roleId = DB::table('roles')
            ->where('slug', $roleSlug)
            ->value('id');

        if (! $roleId) {
            $now = now();
            $roleId = DB::table('roles')->insertGetId([
                'name' => Str::of($roleSlug)->replace('_', ' ')->title()->toString(),
                'slug' => $roleSlug,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        DB::table('role_user')->updateOrInsert(
            ['role_id' => $roleId, 'user_id' => $user->id],
            ['created_at' => now(), 'updated_at' => now()],
        );
    }

    public function detachRole(User $user, string $roleSlug): void
    {
        $roleId = DB::table('roles')
            ->where('slug', $roleSlug)
            ->value('id');

        if (! $roleId) {
            return;
        }

        DB::table('role_user')
            ->where('role_id', $roleId)
            ->where('user_id', $user->id)
            ->delete();
    }
}

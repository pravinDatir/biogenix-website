<?php

namespace App\Services\Authorization;

use App\Models\Authorization\Permission;
use App\Models\Authorization\Role;
use App\Models\Authorization\User;
use Illuminate\Support\Str;

class RolePermissionService
{
    // This returns role slugs for the current user and auto-assigns a default role when needed.
    public function roleSlugsForUser(?User $user): array
    {
        if (! $user) {
            return ['guest'];
        }

        $slugs = $user->roles()->pluck('roles.slug')->all();

        if ($slugs !== []) {
            return $slugs;
        }

        $this->assignDefaultRole($user);

        return $user->fresh()->roles()->pluck('roles.slug')->all();
    }

    // This checks whether a user has the requested role.
    public function hasRole(?User $user, string $roleSlug): bool
    {
        return in_array($roleSlug, $this->roleSlugsForUser($user), true);
    }

    // This checks whether a user has the requested permission after role and override rules are applied.
    public function hasPermission(?User $user, string $permissionSlug): bool
    {
        // Step 1: load the active role slugs for this request.
        $roleSlugs = $this->roleSlugsForUser($user);

        // Step 2: allow guest permissions through the guest role matrix.
        if (! $user) {
            return Permission::query()
                ->where('slug', $permissionSlug)
                ->whereHas('roles', fn ($query) => $query->whereIn('roles.slug', $roleSlugs))
                ->exists();
        }

        // Step 3: stop early when the user has a direct deny override.
        $userOverride = $user->permissionOverrides()
            ->whereHas('permission', fn ($query) => $query->where('slug', $permissionSlug))
            ->first();

        if ($userOverride?->grant_type === 'deny') {
            return false;
        }

        if ($userOverride?->grant_type === 'allow') {
            return true;
        }

        if (in_array('admin', $roleSlugs, true)) {
            return true;
        }

        return Permission::query()
            ->where('slug', $permissionSlug)
            ->whereHas('roles', fn ($query) => $query->whereIn('roles.slug', $roleSlugs))
            ->exists();
    }

    // This returns the final permission slug list after role permissions and user overrides are merged.
    public function permissionSlugsForUser(?User $user): array
    {
        // Load active role slugs for this request
        $roleSlugs = $this->roleSlugsForUser($user);

        // Get all permissions from role matrix
        $permissions = Permission::query()
            ->whereHas('roles', fn ($query) => $query->whereIn('roles.slug', $roleSlugs))
            ->pluck('slug')
            ->flip()
            ->map(fn () => true)
            ->all();

        // Guests do not have user-level overrides
        if (! $user) {
            $result = array_keys($permissions);
            sort($result);
            return $result;
        }

        // Admins receive the full permission list
        if (in_array('admin', $roleSlugs, true)) {
            $permissions = Permission::query()
                ->pluck('slug')
                ->flip()
                ->map(fn () => true)
                ->all();
        }

        // Load all permission overrides for this user (with eager-loaded relationships)
        $userOverrides = $user->permissionOverrides()
            ->with('permission:id,slug')
            ->get();

        // Apply user-level allow and deny overrides
        foreach ($userOverrides as $override) {
            // Get permission slug from already-loaded relationship
            $permission = $override->permission;
            
            if (! $permission || ! $permission->slug) {
                continue;
            }

            if ($override->grant_type === 'deny') {
                unset($permissions[$permission->slug]);
            } else {
                $permissions[$permission->slug] = true;
            }
        }

        // Return clean sorted list
        $result = array_keys($permissions);
        sort($result);

        return $result;
    }

    // This assigns the default role based on the user type.
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

    // This assigns a single role to the user and creates the role first when needed.
    public function assignRole(User $user, string $roleSlug): void
    {
        $role = Role::query()->firstOrCreate(
            ['slug' => $roleSlug],
            ['name' => Str::of($roleSlug)->replace('_', ' ')->title()->toString()],
        );

        $user->roles()->syncWithoutDetaching([$role->id]);
    }

    // This removes a role from the user when it exists.
    public function detachRole(User $user, string $roleSlug): void
    {
        $roleId = Role::query()->where('slug', $roleSlug)->value('id');

        if (! $roleId) {
            return;
        }

        $user->roles()->detach($roleId);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RbacSeeder extends Seeder
{
    /**
     * Seed role and permission data.
     */
    public function run(): void
    {
        $now = now();

        $roles = [
            ['name' => 'Guest', 'slug' => 'guest', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'B2C Customer', 'slug' => 'b2c_customer', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'B2B User', 'slug' => 'b2b_user', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Internal User', 'slug' => 'internal_user', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Admin', 'slug' => 'admin', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delegated Admin', 'slug' => 'delegated_admin', 'created_at' => $now, 'updated_at' => $now],
        ];

        $permissions = [
            ['name' => 'View public products', 'slug' => 'products.view.public', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View B2C products', 'slug' => 'products.view.b2c', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View B2B products', 'slug' => 'products.view.b2b', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View public prices', 'slug' => 'pricing.view.public', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View retail prices', 'slug' => 'pricing.view.retail', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View dealer prices', 'slug' => 'pricing.view.dealer', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View institutional prices', 'slug' => 'pricing.view.institutional', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View contract prices', 'slug' => 'pricing.view.contract', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Generate PI for self', 'slug' => 'pi.generate.self', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Generate PI for basic guest target', 'slug' => 'pi.generate.other.basic', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Generate PI for assigned client', 'slug' => 'pi.generate.other.client', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Place orders', 'slug' => 'orders.place', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Create support tickets', 'slug' => 'tickets.create', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View own tickets', 'slug' => 'tickets.view.own', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View inventory', 'slug' => 'inventory.view', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'View global reports', 'slug' => 'reports.view.global', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manage users', 'slug' => 'users.manage', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manage permissions', 'slug' => 'permissions.manage', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Approve B2B users', 'slug' => 'users.approve_b2b', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Create internal users', 'slug' => 'users.create_internal', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Set user permission overrides', 'slug' => 'users.permissions.override', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Set delegated admin scopes', 'slug' => 'users.delegated.scope', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Impersonate users', 'slug' => 'users.impersonate', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('roles')->upsert($roles, ['slug'], ['name', 'updated_at']);
        DB::table('permissions')->upsert($permissions, ['slug'], ['name', 'updated_at']);

        $roleIds = DB::table('roles')->pluck('id', 'slug');
        $permissionIds = DB::table('permissions')->pluck('id', 'slug');

        $matrix = [
            'guest' => [
                'products.view.public',
                'pricing.view.public',
                'pi.generate.self',
                'pi.generate.other.basic',
            ],
            'b2c_customer' => [
                'products.view.public',
                'products.view.b2c',
                'pricing.view.public',
                'pricing.view.retail',
                'pi.generate.self',
                'orders.place',
                'tickets.create',
                'tickets.view.own',
            ],
            'b2b_user' => [
                'products.view.public',
                'products.view.b2b',
                'pricing.view.public',
                'pricing.view.dealer',
                'pricing.view.institutional',
                'pricing.view.contract',
                'pi.generate.self',
                'pi.generate.other.client',
                'orders.place',
                'tickets.create',
                'tickets.view.own',
            ],
            'internal_user' => [
                'products.view.public',
                'tickets.create',
                'tickets.view.own',
                'inventory.view',
            ],
            'delegated_admin' => [
                'products.view.public',
                'products.view.b2c',
                'products.view.b2b',
                'pricing.view.public',
                'pricing.view.retail',
                'pricing.view.dealer',
                'pricing.view.institutional',
                'pricing.view.contract',
                'pi.generate.self',
                'pi.generate.other.client',
                'orders.place',
                'tickets.create',
                'tickets.view.own',
                'inventory.view',
                'reports.view.global',
                'users.manage',
                'users.approve_b2b',
                'users.create_internal',
                'users.permissions.override',
                'users.delegated.scope',
                'users.impersonate',
            ],
            'admin' => array_keys($permissionIds->toArray()),
        ];

        foreach ($matrix as $roleSlug => $permissionSlugs) {
            $roleId = $roleIds[$roleSlug] ?? null;

            if (! $roleId) {
                continue;
            }

            foreach ($permissionSlugs as $permissionSlug) {
                $permissionId = $permissionIds[$permissionSlug] ?? null;

                if (! $permissionId) {
                    continue;
                }

                DB::table('permission_role')->updateOrInsert(
                    ['permission_id' => $permissionId, 'role_id' => $roleId],
                    ['updated_at' => $now, 'created_at' => $now],
                );
            }
        }
    }
}

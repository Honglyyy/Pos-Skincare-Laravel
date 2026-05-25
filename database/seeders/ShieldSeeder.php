<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        // Create all permissions for resources
        $resources = [
            'Product',
            'Category',
            'Brand',
            'Supplier',
            'Order',
            'OrderDetail',
            'Inventory',
            'Customer',
            'User',
            'Role',
        ];

        $actions = ['ViewAny', 'View', 'Create', 'Update', 'Delete', 'DeleteAny', 'Restore', 'ForceDelete', 'ForceDeleteAny', 'RestoreAny', 'Replicate', 'Reorder'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action}:{$resource}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create super_admin role and assign all permissions
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $superAdminRole->syncPermissions(Permission::all());
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = config('roles_permissions.permissions');

        $definedPermissionKeys = array_keys($permissions);

        Permission::whereNotIn('name', $definedPermissionKeys)->delete();

        foreach ($permissions as $permissionKey => $_) {
            Permission::firstOrCreate(
                ['name' => $permissionKey, 'guard_name' => 'web']
            );
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load roles and permissions configuration
        $rolesPermissions = config('roles_permissions');

        // Get all defined role names from the configuration
        $definedRoleNames = array_keys($rolesPermissions['roles']);

        // Delete roles that are not defined in the configuration
        Role::whereNotIn('name', $definedRoleNames)->delete();

        // Iterate through roles and their descriptions
        foreach ($rolesPermissions['roles'] as $roleName => $_) {
            // Create or update the role
            $role = Role::firstOrCreate(
                ['name' => $roleName, 'guard_name' => 'web']
            );

            // Collect permissions to sync
            $permissionsToSync = [];
            if (isset($rolesPermissions['role_permissions'][$roleName])) {
                foreach ($rolesPermissions['role_permissions'][$roleName] as $permissionKey) {
                    // Ensure the permission exists
                    $permission = Permission::firstOrCreate(
                        ['name' => $permissionKey, 'guard_name' => 'web']
                    );
                    $permissionsToSync[] = $permission->id; // Collect permission IDs
                }
            }

            // Sync permissions with the role
            $role->syncPermissions($permissionsToSync);
        }
    }
}
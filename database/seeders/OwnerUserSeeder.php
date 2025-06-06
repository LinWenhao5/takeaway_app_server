<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class OwnerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);

        $permissions = [
            'manage users',
            'manage products',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $ownerRole->syncPermissions($permissions);

        $ownerUser = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Owner User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $ownerUser->assignRole($ownerRole);
    }
}

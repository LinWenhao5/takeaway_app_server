<?php

namespace Database\Seeders;

use App\Features\Auth\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the "admin" role exists
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('The "admin" role does not exist. Please run RoleSeeder first.');
            return;
        }

        // Create or update the admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign the "admin" role to the user
        $adminUser->assignRole($adminRole);
    }
}
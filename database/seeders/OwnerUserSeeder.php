<?php

namespace Database\Seeders;

use App\Features\User\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class OwnerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure the "owner" role exists
        $ownerRole = Role::where('name', 'owner')->first();

        if (!$ownerRole) {
            $this->command->error('The "owner" role does not exist. Please run RoleSeeder first.');
            return;
        }

        // Create or update the owner user
        $ownerUser = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Owner User',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Assign the "owner" role to the user
        $ownerUser->assignRole($ownerRole);
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);

        // Seed roles and assign permissions
        $this->call(RoleSeeder::class);

        // Seed users and assign roles
        $this->call(AdminUserSeeder::class);
        $this->call(OwnerUserSeeder::class);
    }
}

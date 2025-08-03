<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Features\Store\Models\Store;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        if (Store::count() === 0) {
            Store::create([
                'name'         => 'Zen Sushi Purmerend',
                'phone'        => '029-12345678',
                'street'       => 'Koemarkt',
                'house_number' => '1',
                'postcode'     => '1441 DB',
                'city'         => 'Purmerend',
                'country'      => 'Netherlands',
            ]);
        }
    }
}
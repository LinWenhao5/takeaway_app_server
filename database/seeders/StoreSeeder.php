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
                'name'         => 'Zen Sushi',
                'phone'        => '0647428956',
                'street'       => 'Binnenweg',
                'house_number' => '31',
                'postcode'     => '2101 JB',
                'city'         => 'Heemstede',
                'country'      => 'Netherlands',
            ]);
        }
    }
}
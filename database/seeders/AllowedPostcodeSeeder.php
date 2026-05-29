<?php

namespace Database\Seeders;

use App\Features\Address\Models\AllowedPostcode;  
use Illuminate\Database\Seeder;

class AllowedPostcodeSeeder extends Seeder
{
    public function run(): void
    {
        if (AllowedPostcode::count() === 0) {
            $postcodes = [
            ];

            foreach ($postcodes as $code) {
                AllowedPostcode::firstOrCreate(['postcode_pattern' => $code]);
            }
        }
    }
}

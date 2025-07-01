<?php

namespace Database\Seeders;

use App\Models\AllowedPostcode;
use Illuminate\Database\Seeder;

class AllowedPostcodeSeeder extends Seeder
{
    public function run(): void
    {
        if (AllowedPostcode::count() === 0) {
            $postcodes = [
                '1441',
                '1442',
                '1443',
                '1444',
                '1445',
                '1446',
                '1447',
                '1448',
            ];

            foreach ($postcodes as $code) {
                AllowedPostcode::firstOrCreate(['postcode_pattern' => $code]);
            }
        }
    }
}

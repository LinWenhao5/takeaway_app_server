<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Features\Setting\Models\Setting;

class DeliverySettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['key' => 'delivery_minimum_amount'],
            ['value' => 20.00]
        );

        Setting::updateOrCreate(
            ['key' => 'delivery_fee'],
            ['value' => 2.00]
        );
    }
}
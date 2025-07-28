<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessHourSeeder extends Seeder
{
    public function run()
    {
        $defaultOpen = '09:00';
        $defaultClose = '21:00';

        foreach (range(0, 6) as $weekday) {
            $exists = DB::table('business_hours')->where('weekday', $weekday)->exists();
            if (!$exists) {
                DB::table('business_hours')->insert([
                    'weekday' => $weekday,
                    'open_time' => $defaultOpen,
                    'close_time' => $defaultClose,
                    'is_closed' => false,
                ]);
            }
        }
    }
}
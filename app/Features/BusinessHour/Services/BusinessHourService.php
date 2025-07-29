<?php
namespace App\Features\BusinessHour\Services;

use App\Features\BusinessHour\Models\BusinessHour;
use Carbon\Carbon;

class BusinessHourService
{
    public function getAvailableTimes(string $orderType = 'delivery'): array
    {
        $advance = $orderType === 'pickup' ? 30 : 45;
        $interval = 10;

        $weekday = now()->dayOfWeek;
        $hour = BusinessHour::where('weekday', $weekday)->first();

        if (!$hour || $hour->is_closed) {
            return [];
        }

        $open = substr($hour->open_time, 0, 5);
        $close = substr($hour->close_time, 0, 5);

        $times = [];
        $current = Carbon::createFromFormat('H:i', $open);
        $end = Carbon::createFromFormat('H:i', $close);

        $earliest = now()->addMinutes($advance);

        while ($current < $end) {
            $slot = $current->copy()->setDate(now()->year, now()->month, now()->day);
            if ($slot->greaterThanOrEqualTo($earliest)) {
                $times[] = $current->format('H:i');
            }
            $current->addMinutes($interval);
        }

        return $times;
    }
}
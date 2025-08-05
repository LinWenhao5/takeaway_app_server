<?php
namespace App\Features\BusinessHour\Services;

use App\Features\BusinessHour\Models\BusinessHour;
use Carbon\Carbon;
use App\Features\Order\Enums\OrderType;

class BusinessHourService
{
    public function getAvailableTimesForDate(OrderType $orderType, Carbon $date): array
    {
        $advance = $orderType === OrderType::PICKUP ? 30 : 45;
        $interval = 10;

        $weekday = $date->dayOfWeek;
        $hour = BusinessHour::where('weekday', $weekday)->first();

        if (!$hour || $hour->is_closed) {
            return [];
        }

        $open = substr($hour->open_time, 0, 5);
        $close = substr($hour->close_time, 0, 5);

        $times = [];
        $current = Carbon::createFromFormat('H:i', $open);
        $end = Carbon::createFromFormat('H:i', $close);

        $earliest = $date->isToday() ? now()->addMinutes($advance) : $date->copy()->setTime($current->hour, $current->minute);

        while ($current <= $end) {
            $slot = $date->copy()->setTime($current->hour, $current->minute);
            if ($slot->greaterThanOrEqualTo($earliest)) {
                $times[] = $current->format('H:i');
            }
            $current->addMinutes($interval);
        }

        return $times;
    }

    public function isTimeAvailableForDate(OrderType $orderType, Carbon $date): bool
    {
        $availableTimes = $this->getAvailableTimesForDate($orderType, $date);
        return in_array($date->format('H:i'), $availableTimes);
    }
}
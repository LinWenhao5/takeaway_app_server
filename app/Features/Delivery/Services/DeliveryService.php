<?php

namespace App\Features\Delivery\Services;

use App\Features\Setting\Models\Setting;

class DeliveryService
{
    const MIN_AMOUNT_KEY = 'delivery_minimum_amount';
    const FEE_KEY = 'delivery_fee';

    public function getMinimumAmount()
    {
        return (float) Setting::getValue(self::MIN_AMOUNT_KEY, 0);
    }

    public function setMinimumAmount($amount)
    {
        Setting::updateOrCreate(
            ['key' => self::MIN_AMOUNT_KEY],
            ['value' => $amount]
        );
    }

    public function getFee()
    {
        return (float) Setting::getValue(self::FEE_KEY, 0);
    }

    public function setFee($fee)
    {
        Setting::updateOrCreate(
            ['key' => self::FEE_KEY],
            ['value' => $fee]
        );
    }
}
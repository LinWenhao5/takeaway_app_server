<?php

namespace App\Features\Order\Enums;

enum OrderType: string
{
    case DELIVERY = 'delivery';
    case PICKUP = 'pickup';
}
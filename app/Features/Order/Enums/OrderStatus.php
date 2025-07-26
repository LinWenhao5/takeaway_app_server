<?php
namespace App\Features\Order\Enums;

enum OrderStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case WaitingPickup = 'waiting_pickup';
    case Delivering = 'delivering';
    case Completed = 'completed';
}
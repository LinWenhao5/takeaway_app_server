<?php
namespace App\Features\Order\Enums;

enum OrderStatus: string
{
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Delivering = 'delivering';
    case Completed = 'completed';
}
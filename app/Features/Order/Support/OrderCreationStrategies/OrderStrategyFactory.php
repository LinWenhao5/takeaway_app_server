<?php
namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Order\Enums\OrderType;
use App\Features\Cart\Services\CartService;
use App\Features\Delivery\Services\DeliveryService;

class OrderStrategyFactory
{
    public static function create(
        OrderType $orderType, 
        CartService $cartService, 
        DeliveryService $deliveryService
    ): AbstractOrderCreationStrategy {
        switch ($orderType) {
            case OrderType::DELIVERY:
                return new DeliveryOrderStrategy($cartService, $deliveryService);
            case OrderType::PICKUP:
                return new PickupOrderStrategy($cartService);
            default:
                throw new \Exception('Unsupported order type');
        }
    }
}   
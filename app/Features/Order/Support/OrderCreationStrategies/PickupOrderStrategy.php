<?php
namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Cart\Services\CartService;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;

class PickupOrderStrategy extends AbstractOrderCreationStrategy
{
    public function __construct(CartService $cartService)
    {
        parent::__construct($cartService);
    }

    public function validateOrder($totalPrice, $addressId): void
    {
        // No specific validation for pickup orders
    }

    protected function calculateFinalPrice($totalPrice): float
    {
        return $totalPrice;
    }

    protected function buildOrderData($customerId, $addressId, $reserveTime, $note, $finalPrice): array
    {
        return [
            'customer_id' => $customerId,
            'status' => OrderStatus::Unpaid,
            'total_price' => $finalPrice,
            'delivery_fee' => 0,
            'address_id' => null,
            'address_snapshot' => null,
            'order_type' => OrderType::PICKUP,
            'reserve_time' => $reserveTime,
            'note' => $note,
        ];
    }
}
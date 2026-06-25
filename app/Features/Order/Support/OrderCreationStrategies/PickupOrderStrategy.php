<?php
namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Order\DTOs\CreateOrderDto;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;

use App\Features\Cart\Services\CartService;
use App\Features\Coupon\Services\CouponService;
use App\Features\Vat\Services\VatCalculationService;

class PickupOrderStrategy extends AbstractOrderCreationStrategy
{
    public function __construct (
        CartService $cartService,
        CouponService $couponService,
        VatCalculationService $vatCalculationService
    ) {
        parent::__construct($cartService, $couponService, $vatCalculationService);
    }

    public function validateOrder(CreateOrderDto $createOrderDto, float $totalPrice): void
    {
        // No specific validation for pickup orders
    }

    protected function calculateFinalPrice(float $totalPrice): float
    {
        return $totalPrice;
    }

    protected function buildOrderData(CreateOrderDto $createOrderDto, float $finalPrice): array
    {
        return [
            'customer_id' => $createOrderDto->customerId,
            'status' => OrderStatus::Unpaid,
            'total_price' => $finalPrice,
            'delivery_fee' => 0,
            'address_id' => null,
            'address_snapshot' => null,
            'order_type' => OrderType::PICKUP,
            'reserve_time' => $createOrderDto->reserveTime,
            'note' => $createOrderDto->note,
        ];
    }
}
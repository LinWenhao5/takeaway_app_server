<?php

namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Order\DTOs\CreateOrderDto;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;
use App\Features\Cart\Services\CartService;
use App\Features\Coupon\Services\CouponService;
use App\Features\Vat\Services\VatCalculationService;
use Illuminate\Support\Facades\Auth;
use App\Features\Order\Models\Order;

class WalkInOrderStrategy extends AbstractOrderCreationStrategy
{
    public function __construct(
        CartService $cartService,
        CouponService $couponService,
        VatCalculationService $vatCalculationService
    ) {
        parent::__construct($cartService, $couponService, $vatCalculationService);
    }

    public function validateOrder(CreateOrderDto $createOrderDto, float $subtotal): void
    {
    }


    protected function calculateFinalPrice(float $subtotal): float
    {
        return $subtotal;
    }

    protected function getCartId(?int $customerId): string
    {
        return 'pos:staff:' . (Auth::id() ?? 'default');
    }


    protected function buildOrderData(CreateOrderDto $createOrderDto, float $finalPrice): array
    {
        $lastOrder = Order::where('order_date', today()->toDateString())
        ->orderBy('daily_sequence', 'desc')
        ->lockForUpdate()
        ->first();

        $dailySequence = $lastOrder ? ($lastOrder->daily_sequence + 1) : 1;

        return [
            'customer_id'      => null,
            'status'           => OrderStatus::Paid,
            'total_price'      => $finalPrice,
            'delivery_fee'     => 0,
            'address_id'       => null,
            'address_snapshot' => null,
            'order_type'       => OrderType::WALK_IN,
            'reserve_time'     => now(),
            'note'             => $createOrderDto->note,
            'printed'          => false,
            'order_date'       => today(),
            'daily_sequence'   => $dailySequence,
        ];
    }
}
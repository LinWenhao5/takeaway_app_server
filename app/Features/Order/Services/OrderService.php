<?php
namespace App\Features\Order\Services;

use App\Features\Cart\Services\CartService;
use App\Features\Order\Enums\OrderType;
use App\Features\BusinessHour\Services\BusinessHourService;
use App\Features\Delivery\Services\DeliveryService;
use App\Features\Order\Support\OrderCreationStrategies\OrderStrategyFactory;
use Carbon\Carbon;
use Exception;

class OrderService
{
    protected $cartService;
    protected $businessHourService;
    protected $deliveryService;

    public function __construct(
        CartService $cartService, 
        BusinessHourService $businessHourService,
        DeliveryService $deliveryService
    ) {
        $this->cartService = $cartService;
        $this->businessHourService = $businessHourService;
        $this->deliveryService = $deliveryService;
    }

    public function createOrder($customerId, $addressId, OrderType $orderType, string $reserveTime, $note = null)
    {
        $reserveDate = Carbon::parse($reserveTime);

        if (!$this->businessHourService->isTimeAvailableForDate($orderType, $reserveDate)) {
            throw new Exception('Selected time is not available');
        }

        $strategy = OrderStrategyFactory::create(
            $orderType, 
            $this->cartService, 
            $this->deliveryService
        );

        return $strategy->createOrder($customerId, $addressId, $reserveTime, $note);
    }
}
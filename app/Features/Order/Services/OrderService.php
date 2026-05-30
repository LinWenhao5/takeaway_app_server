<?php
namespace App\Features\Order\Services;

use App\Features\Cart\Services\CartService;
use App\Features\Order\DTOs\CreateOrderDto;
use App\Features\BusinessHour\Services\BusinessHourService;
use App\Features\Delivery\Services\DeliveryService;
use App\Features\Order\Support\OrderCreationStrategies\OrderStrategyFactory;
use Carbon\Carbon;
use Exception;



class OrderService
{
    public function __construct(
        protected CartService $cartService, 
        protected BusinessHourService $businessHourService,
        protected DeliveryService $deliveryService
    ) {
    }

    public function createOrder(CreateOrderDto $createOrderDto)
    {
        $reserveDate = Carbon::parse($createOrderDto->reserveTime);

        if (!$this->businessHourService->isTimeAvailableForDate($createOrderDto->orderType, $reserveDate)) {
            throw new Exception('Selected time is not available');
        }

        $strategy = OrderStrategyFactory::create(
            $createOrderDto->orderType,
            $this->cartService, 
            $this->deliveryService
        );

        return $strategy->createOrder($createOrderDto);
    }
}
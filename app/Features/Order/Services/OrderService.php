<?php
namespace App\Features\Order\Services;

use App\Features\Cart\Services\CartService;
use App\Features\Order\DTOs\CreateOrderDto;
use App\Features\BusinessHour\Services\BusinessHourService;
use App\Features\Delivery\Services\DeliveryService;
use App\Features\Order\Support\OrderCreationStrategies\OrderStrategyFactory;
use Carbon\Carbon;
use App\Exceptions\BusinessException;



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
        $this->cartService->ensureCartProductsAreAvailable($createOrderDto->customerId);

        $reserveDate = Carbon::parse($createOrderDto->reserveTime);

        if (!$this->businessHourService->isTimeAvailableForDate($createOrderDto->orderType, $reserveDate)) {
            throw new BusinessException(
                'Selected reserve time is outside of business hours',
                'RESERVE_TIME_UNAVAILABLE',
                422
            );
        }

        $strategy = OrderStrategyFactory::create(
            $createOrderDto->orderType,
            $this->cartService, 
            $this->deliveryService
        );

        return $strategy->createOrder($createOrderDto);
    }
}
<?php
namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Order\Enums\OrderType;
use Illuminate\Contracts\Container\Container;

class OrderStrategyFactory
{
    public function __construct(
        protected Container $container
    ) {}

    public function create(OrderType $orderType): AbstractOrderCreationStrategy
    {
        switch ($orderType) {
            case OrderType::DELIVERY:
                return $this->container->make(DeliveryOrderStrategy::class);
                
            case OrderType::PICKUP:
                return $this->container->make(PickupOrderStrategy::class);
                
            default:
                throw new \Exception('Unsupported order type');
        }
    }
}
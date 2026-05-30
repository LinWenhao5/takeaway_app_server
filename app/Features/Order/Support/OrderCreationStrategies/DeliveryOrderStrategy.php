<?php
namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Cart\Services\CartService;
use App\Features\Order\DTOs\CreateOrderDto;
use App\Features\Delivery\Services\DeliveryService;
use App\Features\Address\Models\Address;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;
use Exception;

class DeliveryOrderStrategy extends AbstractOrderCreationStrategy
{

    public function __construct(
        protected CartService $cartService, 
        protected DeliveryService $deliveryService
    ) {}

    public function validateOrder(CreateOrderDto $createOrderDto, float $totalPrice): void
    {
        if (!$createOrderDto->addressId) {
            throw new Exception('Address is required for delivery orders');
        }

        $minimumAmount = $this->deliveryService->getMinimumAmount();
        if ($totalPrice < $minimumAmount) {
            throw new Exception('Order amount does not meet the minimum delivery amount: ' . $minimumAmount);
        }
    }

    protected function calculateFinalPrice(float $totalPrice): float
    {
        return $totalPrice + $this->deliveryService->getFee();
    }

    protected function buildOrderData(CreateOrderDto $createOrderDto, float $finalPrice): array
    {
        $address = Address::findOrFail($createOrderDto->addressId);
        $deliveryFee = $this->deliveryService->getFee();

        return [
            'customer_id' => $createOrderDto->customerId,
            'status' => OrderStatus::Unpaid,
            'total_price' => $finalPrice,
            'delivery_fee' => $deliveryFee,
            'address_id' => $address->id,
            'address_snapshot' => $this->makeAddressSnapshot($address),
            'order_type' => OrderType::DELIVERY,
            'reserve_time' => $createOrderDto->reserveTime,
            'note' => $createOrderDto->note,
        ];
    }

    private function makeAddressSnapshot(Address $address): array
    {
        return [
            'name' => $address->name,
            'phone' => $address->phone,
            'street' => $address->street,
            'house_number' => $address->house_number,
            'postcode' => $address->postcode,
            'city' => $address->city,
            'country' => $address->country,
        ];
    }
}
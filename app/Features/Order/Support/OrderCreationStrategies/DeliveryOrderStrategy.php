<?php
namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Cart\Services\CartService;
use App\Features\Delivery\Services\DeliveryService;
use App\Features\Address\Models\Address;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;
use Exception;

class DeliveryOrderStrategy extends AbstractOrderCreationStrategy
{
    protected DeliveryService $deliveryService;

    public function __construct(CartService $cartService, DeliveryService $deliveryService)
    {
        parent::__construct($cartService);
        $this->deliveryService = $deliveryService;
    }

    public function validateOrder($totalPrice, $addressId): void
    {
        if (!$addressId) {
            throw new Exception('Address is required for delivery orders');
        }

        $minimumAmount = $this->deliveryService->getMinimumAmount();
        if ($totalPrice < $minimumAmount) {
            throw new Exception('Order amount does not meet the minimum delivery amount: ' . $minimumAmount);
        }
    }

    protected function calculateFinalPrice($totalPrice): float
    {
        return $totalPrice + $this->deliveryService->getFee();
    }

    protected function buildOrderData($customerId, $addressId, $reserveTime, $note, $finalPrice): array
    {
        $address = Address::findOrFail($addressId);
        $deliveryFee = $this->deliveryService->getFee();

        return [
            'customer_id' => $customerId,
            'status' => OrderStatus::Unpaid,
            'total_price' => $finalPrice,
            'delivery_fee' => $deliveryFee,
            'address_id' => $address->id,
            'address_snapshot' => $this->makeAddressSnapshot($address),
            'order_type' => OrderType::DELIVERY,
            'reserve_time' => $reserveTime,
            'note' => $note,
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
<?php
namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Cart\Services\CartService;
use App\Features\Order\DTOs\CreateOrderDto;
use App\Features\Address\Models\Address;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;
use App\Features\Address\Models\AllowedPostcode;
use App\Exceptions\BusinessException;

use App\Features\Coupon\Services\CouponService;
use App\Features\Vat\Services\VatCalculationService;
use App\Features\Delivery\Services\DeliveryService;

class DeliveryOrderStrategy extends AbstractOrderCreationStrategy
{

    public function __construct(
        CartService $cartService,
        CouponService $couponService,
        VatCalculationService $vatCalculationService,
        protected DeliveryService $deliveryService
    ) {
        parent::__construct($cartService, $couponService, $vatCalculationService);
    }

    public function validateOrder(CreateOrderDto $createOrderDto, float $totalPrice): void
    {
        if (!$createOrderDto->addressId) {
            throw new BusinessException(
                'Address is required for delivery orders',
                'ADDRESS_REQUIRED',
                422
            );
        }

        $address = Address::find($createOrderDto->addressId);
        if (!AllowedPostcode::isAllowed($address->postcode)) {
            throw new BusinessException(
                'Delivery is not available to the provided postcode',
                'DELIVERY_UNAVAILABLE',
                422
            );
        }

        $minimumAmount = $this->deliveryService->getMinimumAmount();
        if ($totalPrice < $minimumAmount) {
            throw new BusinessException(
                'Order amount does not meet the minimum delivery amount',
                'ORDER_AMOUNT_BELOW_MINIMUM',
                422
            );
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
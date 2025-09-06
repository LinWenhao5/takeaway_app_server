<?php
namespace App\Features\Order\Services;

use App\Features\Cart\Services\CartService;
use App\Features\Product\Models\Product;
use App\Features\Order\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Features\Address\Models\Address;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;
use App\Features\BusinessHour\Services\BusinessHourService;
use App\Features\Delivery\Services\DeliveryService;
use Carbon\Carbon;
use Exception;

Class OrderService
{
    protected $cartService;
    protected $businessHourService;
    protected $deliveryService;

    public function __construct(
        CartService $cartService, 
        BusinessHourService $businessHourService,
        DeliveryService $deliveryService
    )
    {
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

        if ($orderType === OrderType::DELIVERY) {
            return $this->createDeliveryOrder($customerId, $addressId, $reserveTime, $note);
        } elseif ($orderType === OrderType::PICKUP) {
            return $this->createPickupOrder($customerId, $reserveTime, $note);
        } else {
            throw new Exception('Invalid order type');
        }
    }

    private function makeSnapshot(Address $address): array
    {
        return [
            'name'         => $address->name,
            'phone'        => $address->phone,
            'street'       => $address->street,
            'house_number' => $address->house_number,
            'postcode'     => $address->postcode,
            'city'         => $address->city,
            'country'      => $address->country,
        ];
    }

    private function prepareCartProductsAndTotal($customerId)
    {
        $cart = $this->cartService->getCart($customerId);
        if (empty($cart)) {
            throw new Exception('Cart is empty');
        }

        $totalPrice = 0;
        $products = [];
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if (!$product) {
                throw new Exception('Product not found: ' . $productId);
            }
            $totalPrice += $product->price * $quantity;
            $products[$productId] = $product;
        }

        return [$cart, $products, $totalPrice];
    }

    private function createDeliveryOrder($customerId, $addressId, $reserveTime, $note = null)
    {
        return DB::transaction(function () use ($customerId, $addressId, $reserveTime, $note) {
            [$cart, $products, $totalPrice] = $this->prepareCartProductsAndTotal($customerId);

            $minimumAmount = $this->deliveryService->getMinimumAmount();
            if ($totalPrice < $minimumAmount) {
                throw new Exception('Order amount does not meet the minimum delivery amount: ' . $minimumAmount);
            }

            $address = Address::findOrFail($addressId);
            $addressSnapshot = $this->makeSnapshot($address);

            $order = Order::create([
                'customer_id' => $customerId,
                'status' => OrderStatus::Unpaid,
                'total_price' => $totalPrice,
                'address_id' => $address?->id,
                'address_snapshot' => $addressSnapshot,
                'order_type' => OrderType::DELIVERY,
                'reserve_time' => $reserveTime,
                'note' => $note,
            ]);

            foreach ($cart as $productId => $quantity) {
                $order->products()->attach($productId, [
                    'quantity' => $quantity,
                    'price' => $products[$productId]->price,
                ]);
            }

            $this->cartService->clearCart($customerId);

            return $order;
        });
    }

    private function createPickupOrder($customerId, $reserveTime, $note = null)
    {
        return DB::transaction(function () use ($customerId, $reserveTime, $note) {
            [$cart, $products, $totalPrice] = $this->prepareCartProductsAndTotal($customerId);

            $order = Order::create([
                'customer_id' => $customerId,
                'status' => OrderStatus::Unpaid,
                'total_price' => $totalPrice,
                'address_id' => null,
                'address_snapshot' => null,
                'order_type' => OrderType::PICKUP,
                'reserve_time' => $reserveTime,
                'note' => $note,
            ]);

            foreach ($cart as $productId => $quantity) {
                $order->products()->attach($productId, [
                    'quantity' => $quantity,
                    'price' => $products[$productId]->price,
                ]);
            }

            $this->cartService->clearCart($customerId);

            return $order;
        });
    }
}
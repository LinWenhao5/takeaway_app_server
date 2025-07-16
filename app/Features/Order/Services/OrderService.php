<?php
namespace App\Features\Order\Services;

use App\Features\Cart\Services\CartService;
use App\Features\Product\Models\Product;
use App\Features\Order\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Features\Address\Models\Address;
use App\Features\Order\Enums\OrderStatus;
use Exception;

Class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createOrder($customerId, $addressId)
    {
        return DB::transaction(function () use ($customerId, $addressId) {
            $cart = $this->cartService->getCart($customerId);
            if (empty($cart)) {
                throw new Exception('Cart is empty');
            }

            $address = Address::findOrFail($addressId);

            $totalPrice = 0;
            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                if (!$product) {
                    throw new Exception('Product not found: ' . $productId);
                }
                $totalPrice += $product->price * $quantity;
                $products[$productId] = $product;
            }

            $addressSnapshot = [
                'name' => $address->name,
                'phone' => $address->phone,
                'street' => $address->street,
                'house_number' => $address->house_number,
                'postcode' => $address->postcode,
                'city' => $address->city,
                'country' => $address->country,
            ];

            $order = Order::create([
                'customer_id' => $customerId,
                'status' => OrderStatus::Unpaid,
                'total_price' => $totalPrice,
                'address_id' => $address->id,
                'address_snapshot' => $addressSnapshot,
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
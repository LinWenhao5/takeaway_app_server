<?php
namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Cart\Services\CartService;
use App\Features\Product\Models\Product;
use App\Features\Order\Models\Order;
use Illuminate\Support\Facades\DB;
use Exception;

abstract class AbstractOrderCreationStrategy 
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createOrder($customerId, $addressId, $reserveTime, $note = null): Order
    {
        return DB::transaction(function () use ($customerId, $addressId, $reserveTime, $note) {
            [$cart, $products, $totalPrice] = $this->prepareCartProductsAndTotal($customerId);
            
            $this->validateOrder($totalPrice, $addressId);
            
            $finalPrice = $this->calculateFinalPrice($totalPrice);
            
            $orderData = $this->buildOrderData($customerId, $addressId, $reserveTime, $note, $finalPrice);
            
            $order = Order::create($orderData);

            $this->attachProductsToOrder($order, $cart, $products);

            $this->cartService->clearCart($customerId);

            return $order;
        });
    }

    protected function prepareCartProductsAndTotal($customerId): array
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

    protected function attachProductsToOrder(Order $order, array $cart, array $products): void
    {
        foreach ($cart as $productId => $quantity) {
            $order->products()->attach($productId, [
                'quantity' => $quantity,
                'price' => $products[$productId]->price,
            ]);
        }
    }

    abstract public function validateOrder($totalPrice, $addressId): void;
    
    abstract protected function calculateFinalPrice($totalPrice): float;
    
    abstract protected function buildOrderData($customerId, $addressId, $reserveTime, $note, $finalPrice): array;
}
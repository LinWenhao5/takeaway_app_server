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

            $vatSummary = $this->calculateVatSummary($order);

            $order->update(['vat_snapshot' => $vatSummary]);

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

            $vatRate = $product->vatRate?->rate ?? 0;
            $vatName = $product->vatRate?->name ?? 'No VAT';
            $vatAmount = round($product->price * $vatRate / 100, 2);

            $product->vat_rate = $vatRate;
            $product->vat_amount = $vatAmount;
            $product->vat_name = $vatName;

            $totalPrice += $product->price * $quantity;
            $products[$productId] = $product;
        }

        return [$cart, $products, $totalPrice];
    }

    protected function attachProductsToOrder(Order $order, array $cart, array $products): void
    {
        foreach ($cart as $productId => $quantity) {
            $product = $products[$productId];
            $order->products()->attach($productId, [
                'quantity' => $quantity,
                'price' => $product->price,
                'vat_amount' => $product->vat_amount,
                'vat_rate' => $product->vat_rate,
                'vat_name' => $product->vat_name,
            ]);
        }
    }

    abstract protected function calculateVatSummary(Order $order): array;

    abstract public function validateOrder($totalPrice, $addressId): void;
    
    abstract protected function calculateFinalPrice($totalPrice): float;
    
    abstract protected function buildOrderData($customerId, $addressId, $reserveTime, $note, $finalPrice): array;
}
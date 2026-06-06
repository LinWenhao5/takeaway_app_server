<?php
namespace App\Features\Order\Support\OrderCreationStrategies;

use App\Features\Cart\Services\CartService;
use App\Features\Order\DTOs\CreateOrderDto;
use App\Features\Product\Models\Product;
use App\Features\Order\Models\Order;
use Illuminate\Support\Facades\DB;
use Exception;

abstract class AbstractOrderCreationStrategy 
{
    public function __construct( 
        protected CartService $cartService
    )
    {}

    public function createOrder(CreateOrderDto $createOrderDto): Order
    {
        return DB::transaction(function () use ($createOrderDto) {
            [$cart, $products, $totalPrice] = $this->prepareCartProductsAndTotal($createOrderDto->customerId);
            
            $this->validateOrder($createOrderDto, $totalPrice);
            
            $finalPrice = $this->calculateFinalPrice($totalPrice);
            
            $orderData = $this->buildOrderData($createOrderDto, $finalPrice);
            
            $order = Order::create($orderData);

            $this->attachProductsToOrder($order, $cart, $products);

            $vatSummary = $this->calculateVatSummary($order);
            $totalVatAmount = $vatSummary['total_vat_amount'] ?? 0;

            $order->update([
                'vat_snapshot' => $vatSummary,
                'total_vat_amount' => $totalVatAmount,
            ]);

            $this->cartService->clearCart($createOrderDto->customerId);

            return $order;
        });
    }

    protected function prepareCartProductsAndTotal(int $customerId): array
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
        $productsSnapshot = [];
        foreach ($cart as $productId => $quantity) {
            $product = $products[$productId];
            $order->products()->attach($productId, [
                'quantity' => $quantity,
                'price' => $product->price,
            ]);

            $productsSnapshot[] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'vat_rate' => $product->vat_rate,
                'vat_amount' => $product->vat_amount,
                'vat_name' => $product->vat_name,
                'quantity' => $quantity,
            ];
        }

        $order->update([
            'products_snapshot' => $productsSnapshot,
        ]);
    }

    protected function calculateVatSummary(Order $order): array
    {
        $vatSummary = [];
        $totalVatAmount = 0;

        foreach ($order->products as $product) {
            $vatName = $product->pivot->vat_name ?? 'No VAT';
            $vatAmount = $product->pivot->vat_amount * $product->pivot->quantity;
            $productAmount = $product->pivot->price * $product->pivot->quantity;

            if (!isset($vatSummary[$vatName])) {
                $vatSummary[$vatName] = [
                    'vat_total' => 0,
                    'product_total' => 0,
                ];
            }
            $vatSummary[$vatName]['vat_total'] += $vatAmount;
            $vatSummary[$vatName]['product_total'] += $productAmount;
            $totalVatAmount += $vatAmount;
        }

        $vatSummary['total_vat_amount'] = $totalVatAmount;
        return $vatSummary;
    }

    abstract public function validateOrder(CreateOrderDto $createOrderDto, float $totalPrice): void;
    
    abstract protected function calculateFinalPrice(float $totalPrice): float;
    
    abstract protected function buildOrderData(CreateOrderDto $createOrderDto, float $finalPrice): array;
}
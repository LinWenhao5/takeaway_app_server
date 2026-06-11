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
    ) {}

    public function createOrder(CreateOrderDto $createOrderDto): Order
    {
        return DB::transaction(function () use ($createOrderDto) {

            [$cart, $products, $subtotal] =
                $this->prepareCartProducts($createOrderDto->customerId);

            $this->validateOrder($createOrderDto, $subtotal);

            $finalPrice = $this->calculateFinalPrice($subtotal);

            $order = Order::create(
                $this->buildOrderData($createOrderDto, $finalPrice)
            );

            $this->attachProductsToOrder($order, $cart, $products);

            $vatSummary = $this->calculateVatSummary($cart, $products);

            $order->update([
                'vat_snapshot' => $vatSummary,
                'total_vat_amount' => $vatSummary['total_vat_amount'],
            ]);

            $this->cartService->clearCart($createOrderDto->customerId);

            return $order;
        });
    }

    /**
     * Step 1: Prepare cart + products + subtotal
     */
    protected function prepareCartProducts(int $customerId): array
    {
        $cart = $this->cartService->getCart($customerId);

        if (empty($cart)) {
            throw new Exception('Cart is empty');
        }

        $subtotal = 0;
        $products = [];

        foreach ($cart as $productId => $quantity) {

            $product = Product::find($productId);

            if (!$product) {
                throw new Exception('Product not found: ' . $productId);
            }

            $products[$productId] = [
                'model' => $product,
                'quantity' => $quantity,
                'unit_price' => $product->final_price,
            ];

            $subtotal += $product->final_price * $quantity;
        }

        return [$cart, $products, $subtotal];
    }

    /**
     * Step 2: Attach order items + snapshot
     */
    protected function attachProductsToOrder(Order $order, array $cart, array $products): void
    {
        $snapshot = [];

        foreach ($cart as $productId => $quantity) {

            $data = $products[$productId];
            $product = $data['model'];

            $order->products()->attach($productId, [
                'quantity' => $quantity,
                'price' => $product->price,
                'final_price' => $product->final_price,
            ]);

            $snapshot[] = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,

                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'final_price' => $product->final_price,

                'is_discounted' => $product->is_discounted,

                'vat_rate' => $product->vatRate?->rate,
                'quantity' => $quantity,
            ];
        }

        $order->update([
            'products_snapshot' => $snapshot,
        ]);
    }

    /**
     * Step 3: VAT calculation (ONLY PLACE VAT IS CALCULATED)
     */
    protected function calculateVatSummary(array $cart, array $products): array
    {
        $summary = [];
        $totalVat = 0;

        foreach ($cart as $productId => $quantity) {

            $data = $products[$productId];
            $product = $data['model'];

            $vatRate = $product->vatRate?->rate ?? 0;
            $vatName = $product->vatRate?->name ?? 'No VAT';

            $subtotal = $product->final_price * $quantity;
            $vatAmount = round($subtotal * ($vatRate / 100), 2);

            if (!isset($summary[$vatName])) {
                $summary[$vatName] = [
                    'vat_total' => 0,
                    'product_total' => 0,
                ];
            }

            $summary[$vatName]['vat_total'] += $vatAmount;
            $summary[$vatName]['product_total'] += $subtotal;

            $totalVat += $vatAmount;
        }

        $summary['total_vat_amount'] = round($totalVat, 2);

        return $summary;
    }

    /**
     * Abstract methods
     */
    abstract public function validateOrder(CreateOrderDto $createOrderDto, float $subtotal): void;

    abstract protected function calculateFinalPrice(float $subtotal): float;

    abstract protected function buildOrderData(CreateOrderDto $createOrderDto, float $finalPrice): array;
}
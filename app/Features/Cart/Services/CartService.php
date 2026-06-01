<?php

namespace App\Features\Cart\Services;

use Illuminate\Support\Facades\Redis;
use App\Features\Product\Models\Product;
use App\Exceptions\ProductNotAvailableException;

class CartService
{
    protected $cartKeyPrefix = 'cart:';
    protected $cartExpiration = 86400;

    public function addToCart($customerId, $productId, $quantity)
    {
        $this->ensureProductIsAvailable($productId);

        $cartKey = $this->cartKeyPrefix . $customerId;

        Redis::connection('cache')->hincrby($cartKey, $productId, $quantity);

        Redis::connection('cache')->expire($cartKey, $this->cartExpiration);
    }

    public function removeQuantityFromCart($customerId, $productId, $quantity)
    {
        $cartKey = $this->cartKeyPrefix . $customerId;
        $currentQuantity = Redis::connection('cache')->hget($cartKey, $productId);

        if ($currentQuantity !== null) {
            $newQuantity = $currentQuantity - $quantity;
            if ($newQuantity > 0) {
                Redis::connection('cache')->hset($cartKey, $productId, $newQuantity);
            } else {
                Redis::connection('cache')->hdel($cartKey, $productId);
            }
        }
    }

    public function removeFromCart($customerId, $productId)
    {
        $cartKey = $this->cartKeyPrefix . $customerId;
        
        Redis::connection('cache')->hdel($cartKey, $productId);
    }

    public function getCart($customerId)
    {
        $cartKey = $this->cartKeyPrefix . $customerId;

        return Redis::connection('cache')->hgetall($cartKey);
    }

    public function clearCart($customerId)
    {
        $cartKey = $this->cartKeyPrefix . $customerId;

        Redis::connection('cache')->del($cartKey);
    }


    public function getCartDetails($customerId)
    {
        $cart = $this->getCart($customerId);

        if (empty($cart)) {
            return [
                'cart' => [],
                'total_price' => '0.00',
                'total_quantity' => '0',
            ];
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->select(
            'id', 
            'name', 
            'description', 
            'price',
            'is_out_of_stock'
        )->with('media')->get();

        $totalPrice = 0;
        $totalQuantity = 0;
        $cartDetails = $products->map(function ($product) use ($cart, &$totalPrice, &$totalQuantity) {
            $quantity = $cart[$product->id];
            $subtotal = $product->price * $quantity;
            $totalPrice += $subtotal;
            $totalQuantity += $quantity;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => (string) $product->price,
                'image' => $product->media->first()->path ?? null,
                'quantity' => (string) $quantity,
                'subtotal' => number_format($subtotal, 2, '.', ''),
                'is_out_of_stock' => $product->is_out_of_stock,
            ];
        });

        return [
            'cart' => $cartDetails,
            'total_price' => number_format($totalPrice, 2, '.', ''),
            'total_quantity' => (string) $totalQuantity,
        ];
    }

    public function ensureProductIsAvailable($productId): void
    {
        $product = Product::select('id', 'name', 'is_out_of_stock')->find($productId);

        if (!$product) {
            throw new ProductNotAvailableException("Product with ID {$productId} not found", 404);
        }

        if ($product->is_out_of_stock) {
            throw new ProductNotAvailableException("Product '{$product->name}' is not available", 409);
        }
    }

    public function ensureCartProductsAreAvailable($customerId): void
    {
        $cart = $this->getCart($customerId);
        if (empty($cart)) return;

        $cartProductIds = array_keys($cart);
        $products = Product::select('id', 'name', 'is_out_of_stock')
            ->whereIn('id', $cartProductIds)
            ->get();

        $existingProductIds = $products->pluck('id')->map(fn($id) => (string)$id)->toArray();
        $missingProductIds = array_diff($cartProductIds, $existingProductIds);
        
        $outOfStockProductIds = $products->where('is_out_of_stock', true)->pluck('id')->map(fn($id) => (string)$id)->toArray();

        $invalidIds = array_merge($missingProductIds, $outOfStockProductIds);

        if (!empty($invalidIds)) {
            $cartKey = $this->cartKeyPrefix . $customerId;
            foreach ($invalidIds as $id) {
                Redis::connection('cache')->hdel($cartKey, $id);
            }
            
            throw new ProductNotAvailableException("Part of products is removed form cart", 409);
        }
    }
}
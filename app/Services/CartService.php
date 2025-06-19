<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use App\Models\Product;

class CartService
{
    protected $cartKeyPrefix = 'cart:';
    protected $cartExpiration = 86400;

    public function addToCart($customerId, $productId, $quantity)
    {
        $cartKey = $this->cartKeyPrefix . $customerId;

        Redis::connection('cache')->hincrby($cartKey, $productId, $quantity);

        Redis::connection('cache')->expire($cartKey, $this->cartExpiration);
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
                'total_price' => 0,
            ];
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->select(
            'id', 
            'name', 
            'description', 
            'price'
        )->with('media')->get();

        $totalPrice = 0;
        $cartDetails = $products->map(function ($product) use ($cart, &$totalPrice) {
            $quantity = $cart[$product->id];
            $subtotal = $product->price * $quantity;
            $totalPrice += $subtotal;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'image' => $product->media->first()->path ?? null,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];
        });

        return [
            'cart' => $cartDetails,
            'total_price' => $totalPrice,
        ];
    }
}
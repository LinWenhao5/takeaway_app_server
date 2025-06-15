<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

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
}
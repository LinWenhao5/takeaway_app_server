<?php

namespace App\Http\Controllers\Cart;

use App\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartService->addToCart(
            $validated['customer_id'],
            $validated['product_id'],
            $validated['quantity']
        );

        return response()->json(['message' => 'Product added to cart successfully.']);
    }

    public function getCart($customerId)
    {
        $cart = $this->cartService->getCart($customerId);

        return response()->json($cart);
    }

    public function removeFromCart(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|integer',
            'product_id' => 'required|integer',
        ]);

        $this->cartService->removeFromCart(
            $validated['customer_id'],
            $validated['product_id']
        );

        return response()->json(['message' => 'Product removed from cart successfully.']);
    }
}
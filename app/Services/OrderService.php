<?php
namespace App\Services;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

Class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createOrder($customerId)
    {
        return DB::transaction(function () use ($customerId) {
            $cart = $this->cartService->getCart($customerId);
            if (empty($cart)) {
                throw new \Exception('Cart is empty');
            }

            $totalPrice = 0;
            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                if (!$product) {
                    throw new \Exception('Product not found: ' . $productId);
                }
                $totalPrice += $product->price * $quantity;
                $products[$productId] = $product;
            }

            $order = Order::create([
                'customer_id' => $customerId,
                'status' => 'pending',
                'total_price' => $totalPrice,
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
<?php
namespace App\Services;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Address;
use Mollie\Laravel\Facades\Mollie;
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
                'status' => 'pending',
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


     public function createPayment(Order $order)
    {
        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => number_format($order->total_price, 2, '.', ''),
            ],
            "description" => "Order #{$order->id}",
            "redirectUrl" => route('orders.payment.callback', $order),
            "webhookUrl" => route('orders.payment.webhook'),
            "method" => \Mollie\Api\Types\PaymentMethod::IDEAL,
            "metadata" => [
                "order_id" => $order->id,
            ],
        ]);

        $order->payment_id = $payment->id;
        $order->save();

        return $payment->getCheckoutUrl();
    }
}
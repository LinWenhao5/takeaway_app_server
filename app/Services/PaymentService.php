<?php
namespace App\Services;

use Mollie\Laravel\Facades\Mollie;
use App\Models\Order;
use InvalidArgumentException;

class PaymentService
{
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

    public function handleWebhook($paymentId)
    {
        if (!$paymentId) {
            throw new InvalidArgumentException('No payment id');
        }

        $payment = Mollie::api()->payments->get($paymentId);

        $orderId = $payment->metadata->order_id ?? null;
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order && $payment->isPaid() && $order->status !== 'paid') {
                $order->status = 'paid';
                $order->save();
            }
        }
    }
}
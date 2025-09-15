<?php
namespace App\Features\Payment\Services;

use Mollie\Laravel\Facades\Mollie;
use App\Features\Order\Models\Order;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Events\OrderCreated;
use InvalidArgumentException;

class PaymentService
{
    public function createPayment(
        Order $order, 
        ?string $platform = 'app', 
        ?string $host = 'https://takeaway-app-zen-sushi.web.app'
    )
    {
        if ($platform === 'web') {
            $redirectUrl = "{$host}/payment-callback?order_id={$order->id}";
        } else {
            $redirectUrl = "takeawayapp://payment-callback?order_id={$order->id}";
        }

        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => number_format($order->total_price, 2, '.', ''),
            ],
            "description" => "Order #{$order->id}",
            "redirectUrl" => $redirectUrl,
            "webhookUrl" => route('api.payment.webhook'),
            // "webhookUrl" => "https://4995da873038.ngrok-free.app/api/payments/webhook",
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
            if ($order && $payment->isPaid() && $order->status !== OrderStatus::Paid) {
                $order->status = OrderStatus::Paid;
                $order->save();
                event(new OrderCreated($order->toArray()));
            }
        }
    }
}
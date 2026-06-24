<?php
namespace App\Features\Payment\Services;

use Mollie\Laravel\Facades\Mollie;
use App\Features\Order\Models\Order;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Events\OrderCreated;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function createPayment(
        Order $order, 
        ?string $platform = 'app', 
        ?string $host = 'https://takeaway-app-zen-sushi.web.app'
    )
    {
        if ($platform === 'web') {
            $redirectUrl = "{$host}/order/{$order->public_id}";
        } else {
            $redirectUrl = "takeawayapp://payment-callback?order_id={$order->public_id}";
        }

        $payment = Mollie::api()->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => number_format($order->total_price, 2, '.', ''),
            ],
            "description" => "Order #{$order->public_id}",
            "redirectUrl" => $redirectUrl,
            "webhookUrl" => route('api.payment.webhook'),
            // "webhookUrl" => "https://a89a-86-94-222-170.ngrok-free.app/api/payments/webhook",
            "method" => \Mollie\Api\Types\PaymentMethod::IDEAL,
            "metadata" => [
                "public_order_id" => $order->public_id,
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

        $publicOrderId = $payment->metadata->public_order_id ?? null;

        if (!$publicOrderId) {
            return;
        }

        $order = Order::where('public_id', $publicOrderId)->first();

        if (!$order) {
            return;
        }

        if (!$payment->isPaid()) {
            return;
        }

        if ($order->status === OrderStatus::Paid) {
            return;
        }

        DB::transaction(function () use ($order) {
            $order->status = OrderStatus::Paid;

            $orderDate = $order->reserve_time->toDateString();
            $order->order_date = $orderDate;

            $last = Order::where('order_date', $orderDate)
                ->lockForUpdate()
                ->max('daily_sequence');

            $order->daily_sequence = ($last ?? 0) + 1;

            $order->save();
        });

        event(new OrderCreated($order->toArray()));
    }
}
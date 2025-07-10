<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;  
use Mollie\Laravel\Facades\Mollie;

class OrderWebController extends Controller
{
    public function paymentCallback($orderId)
    {
        $order = Order::findOrFail($orderId);
        $payment = Mollie::api()->payments->get($order->payment_id);

        if ($payment->isPaid()) {
            $order->status = 'paid';
            $order->save();
            $status = 'success';
        } else {
            $status = 'failed';
        }

        return view('orders.payment_callback', [
            'order' => $order,
            'status' => $status,
        ]);
    }

}
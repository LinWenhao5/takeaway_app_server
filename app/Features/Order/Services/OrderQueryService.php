<?php
namespace App\Features\Order\Services;

use App\Features\Order\Models\Order;
use Exception;

class OrderQueryService
{
    public function getOrderById($orderId, $customerId, $detail = false)
    {
        $query = Order::query();

        if ($detail) {
            $query->with(['products']);
        }

        $order = $query->find($orderId);

        if (!$order) {
            throw new Exception('Order not found');
        }

        if ($order->customer_id !== $customerId) {
            throw new Exception('No permission to view this order');
        }

        return $order;
    }

    public function getOrdersByCustomerId($customerId, $perPage = 10)
    {
        return Order::with(['products'])
            ->where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
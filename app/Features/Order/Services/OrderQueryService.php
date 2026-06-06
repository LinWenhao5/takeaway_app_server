<?php
namespace App\Features\Order\Services;

use App\Features\Order\Models\Order;
use Exception;

class OrderQueryService
{
    public function getOrderById(int $orderId, int $customerId, bool $detail = false)
    {
        $query = Order::query();

        $order = $query->find($orderId);

        if (!$order) {
            throw new Exception('Order not found');
        }

        if ($order->customer_id !== $customerId) {
            throw new Exception('No permission to view this order');
        }

        return $order;
    }

    public function getOrdersByCustomerId(int $customerId, int $perPage = 10)
    {
        return Order::where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
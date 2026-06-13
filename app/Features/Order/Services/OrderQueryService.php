<?php
namespace App\Features\Order\Services;

use App\Features\Order\Models\Order;
use App\Exceptions\BusinessException;

class OrderQueryService
{
    public function getOrderById(int $orderId, int $customerId)
    {
        $query = Order::query();

        $order = $query->find($orderId);

        if (!$order) {
            throw new BusinessException(
                'Order not found',
                'ORDER_NOT_FOUND',
                404
            );
        }

        if ($order->customer_id !== $customerId) {
            throw new BusinessException(
                'No permission to view this order',
                'NO_PERMISSION',
                403
            );
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
<?php
namespace App\Features\Order\Services;

use App\Features\Order\Models\Order;
use App\Exceptions\BusinessException;

class OrderQueryService
{
    public function getOrderById(string $publicId, int $customerId)
    {
        $order = Order::where('public_id', $publicId)
        ->where('customer_id', $customerId)
        ->first();

        if (!$order) {
            throw new BusinessException(
                'Order not found',
                'ORDER_NOT_FOUND',
                404
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
<?php

namespace App\Features\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Order\Services\OrderQueryService;

class OrderQueryApiController extends Controller
{
    public function __construct( 
        protected OrderQueryService $orderQueryService
    ) {}

    /**
     * Get order status by ID.
     *
     * @OA\Get(
     *     path="/api/orders/{orderId}/status",
     *     summary="Get order status by ID",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the order"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order status retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="order_id", type="integer", example=123),
     *             @OA\Property(property="status", type="string", example="paid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     ),
     * )
     */
    public function getOrderStatus(int $orderId)
    {
        $customerId = $this->getAuthenticatedCustomer()->id;
        $order = $this->orderQueryService->getOrderById($orderId, $customerId);

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'status' => $order->status,
        ]);
    }


    /**
     * Get all orders for the authenticated customer (with pagination).
     *
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get all orders for the authenticated customer (paginated)",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         description="Page number for pagination",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         required=false,
     *         description="Number of orders per page",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Orders retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="orders", type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="total", type="integer", example=50)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     )
     * )
     */
    public function getOrdersByCustomerId()
    {
        $customerId = $this->getAuthenticatedCustomer()->id;
        $perPage = request()->input('per_page', 10);
        $orders = $this->orderQueryService->getOrdersByCustomerId($customerId, $perPage);

        return response()->json([
            'success' => true,
            'orders' => $orders,
        ]);
    }


    /**
     * Get order details by ID.
     *
     * @OA\Get(
     *     path="/api/orders/{orderId}",
     *     summary="Get order details by ID",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the order"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="order", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     )
     * )
     */
    public function getOrderDetail(int $orderId)
    {
        $customerId = $this->getAuthenticatedCustomer()->id;
        $order = $this->orderQueryService->getOrderById($orderId, $customerId);

        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }
}
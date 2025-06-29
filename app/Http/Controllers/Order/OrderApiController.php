<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderApiController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * @OA\Post(
     *     path="/api/order/create",
     *     summary="Create a new order",
     *     tags={"Order"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="order_id", type="integer", example=123)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Error message")
     *         )
     *     ),
     * )
     */
    public function createOrder()
    {
        $customerId = $this->getAuthenticatedCustomer()->id;
        try {
            $order = $this->orderService->createOrder($customerId);
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}

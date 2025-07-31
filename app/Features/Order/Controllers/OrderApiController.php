<?php

namespace App\Features\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Order\Services\OrderService;
use App\Features\Payment\Services\PaymentService;
use Illuminate\Http\Request;
use Exception;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;
use App\Features\Order\Services\OrderQueryService;

class OrderApiController extends Controller
{
    protected $orderService;
    protected $paymentService;
    protected $orderQueryService;

    public function __construct(OrderService $orderService, PaymentService $paymentService, OrderQueryService $orderQueryService)
    {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
        $this->orderQueryService = $orderQueryService;
    }

    /**
     * Create a new order and return payment URL.
     *
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Create a new order and get payment URL",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"order_type", "reserve_time"},
     *             @OA\Property(
     *                 property="order_type",
     *                 type="string",
     *                 enum={"delivery", "pickup"},
     *                 description="Order type: delivery or pickup"
     *             ),
     *             @OA\Property(
     *                 property="address_id",
     *                 type="integer",
     *                 example=1,
     *                 description="Required if order_type is delivery. Address ID for delivery orders"
     *             ),
     *             @OA\Property(
     *                 property="reserve_time",
     *                 type="string",
     *                 example="18:30",
     *                 description="Reserve time for delivery or pickup"
     *             ),
     *             @OA\Property(
     *                 property="platform",
     *                 type="string",
     *                 example="web",
     *                 description="Platform (optional)"
     *             ),
     *             @OA\Property(
     *                 property="host",
     *                 type="string",
     *                 example="example.com",
     *                 description="Request host (optional)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="order_id", type="integer", example=123),
     *             @OA\Property(property="payment_url", type="string", example="https://www.mollie.com/paymentscreen/example")
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
    public function createOrder(Request $request)
    {
        $customerId = $this->getAuthenticatedCustomer()->id;

        $validated = $request->validate([
            'order_type' => 'required|string|in:delivery,pickup',
            'address_id' => 'required_if:order_type,delivery|integer|exists:addresses,id',
            'reserve_time' => 'required|date_format:Y-m-d H:i',
        ]);

        $orderType = OrderType::from($validated['order_type']);
        $addressId = $orderType === OrderType::DELIVERY ? $validated['address_id'] : null;
        $reserveTime = $validated['reserve_time'];

        try {
            $order = $this->orderService->createOrder($customerId, $addressId, $orderType, $reserveTime);

            $platform = $request->input('platform');
            $host = $request->input('host');
            $paymentUrl = $this->paymentService->createPayment($order, $platform, $host);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'payment_url' => $paymentUrl,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Repay an unpaid order and get a new payment URL.
     *
     * @OA\Post(
     *     path="/api/orders/{orderId}/repay",
     *     summary="Repay an unpaid order and get a new payment URL",
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
     *         description="Payment URL generated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="order_id", type="integer", example=123),
     *             @OA\Property(property="payment_url", type="string", example="https://www.mollie.com/paymentscreen/example")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order already paid or not found")
     *         )
     *     )
     * )
     */
    public function repayOrder(Request $request, $orderId)
    {
        try {
            $customerId = $this->getAuthenticatedCustomer()->id;
            $order = $this->orderQueryService->getOrderById($orderId, $customerId, detail: true);

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            $isUnpaid = false;
            if ($order->status instanceof OrderStatus) {
                $isUnpaid = $order->status === OrderStatus::Unpaid;
            } else {
                $isUnpaid = $order->status === OrderStatus::Unpaid->value;
            }

            if (!$isUnpaid) {
                return response()->json(['success' => false, 'message' => 'Order already paid or cannot be repaid'], 400);
            }

            $platform = $request->input('platform');
            $host = $request->input('host');

            $paymentUrl = $this->paymentService->createPayment($order, $platform, $host);

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'payment_url' => $paymentUrl,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }


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
    public function getOrderStatus($orderId)
    {
        try {
            $customerId = $this->getAuthenticatedCustomer()->id;
            $order = $this->orderQueryService->getOrderById($orderId, $customerId);
            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'status' => $order->status,
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
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
    public function getOrderDetail($orderId)
    {
        try {
            $customerId = $this->getAuthenticatedCustomer()->id;
            $order = $this->orderQueryService->getOrderById($orderId, $customerId, detail: true);
            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            return response()->json([
                'success' => true,
                'order' => $order,
            ]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
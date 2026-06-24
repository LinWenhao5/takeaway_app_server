<?php

namespace App\Features\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Order\Services\OrderQueryService;
use App\Features\Payment\Services\PaymentService;
use Illuminate\Http\Request;
use App\Features\BusinessHour\Services\BusinessHourService;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;
use App\Features\Order\DTOs\CreateOrderDto;
use App\Features\Order\Services\OrderService;
use Carbon\Carbon;
use App\Exceptions\BusinessException;

class OrderPaymentApiController extends Controller
{

    public function __construct(
        protected OrderQueryService $orderQueryService,
        protected OrderService $orderService,
        protected PaymentService $paymentService,
        protected BusinessHourService $businessHourService
    ) {
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
     *                 example="2025-09-06 18:30",
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
    *             ),
     *             @OA\Property(
    *                 property="note",
    *                 type="string",
    *                 example="Please add extra wasabi",
    *                 description="Additional note for the order (optional)"
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
            'address_id' => 'required_if:order_type,delivery|nullable|integer|exists:addresses,id',
            'reserve_time' => 'required|date_format:Y-m-d H:i',
            'note' => 'nullable|string|max:255',
        ]);

        $orderType = OrderType::from($validated['order_type']);
        $addressId = $orderType === OrderType::DELIVERY ? $validated['address_id'] : null;
        $reserveTime = $validated['reserve_time'];
        $note = $validated['note'] ?? null;

        $createOrderDto = new CreateOrderDto(
            customerId: $customerId,
            addressId: $addressId,
            orderType: $orderType,
            reserveTime: $reserveTime,
            note: $note,
        );

        $order = $this->orderService->createOrder($createOrderDto);

        $platform = $request->input('platform');
        $host = $request->input('host');
        $paymentUrl = $this->paymentService->createPayment($order, $platform, $host);

        return response()->json([
            'success' => true,
            'public_id' => $order->public_id,
            'payment_url' => $paymentUrl,
        ]);
    }

    /**
     * Repay an unpaid order and get a new payment URL.
     *
     * @OA\Post(
     *     path="/api/orders/{publicId}/repay",
     *     summary="Repay an unpaid order and get a new payment URL",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="publicId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Public ID of the order"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment URL generated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="public_id", type="string", example="ORDER123"),
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
    public function repayOrder(Request $request, string $publicId)
    {
        $customerId = $this->getAuthenticatedCustomer()->id;
        $order = $this->orderQueryService->getOrderById($publicId, $customerId);

        if (!$order) {
            throw new BusinessException(
                'Order not found',
                'ORDER_NOT_FOUND',
                404
            );
        }

        if ($order->status !== OrderStatus::Unpaid) {
            throw new BusinessException(
                'Order already paid or not in unpaid status',
                'ORDER_ALREADY_PAID',
                409
            );
        }

        $reserveTime = Carbon::parse($order->reserve_time);
        $orderType = $order->order_type;

        if (!$this->businessHourService->isTimeAvailableForDate($orderType, $reserveTime)) {
            throw new BusinessException(
                'Selected reserve time is outside of business hours',
                'RESERVE_TIME_UNAVAILABLE',
                422
            );
        }

        $platform = $request->input('platform');
        $host = $request->input('host');

        $paymentUrl = $this->paymentService->createPayment($order, $platform, $host);

        return response()->json([
            'success' => true,
            'public_id' => $order->public_id,
            'payment_url' => $paymentUrl,
        ], 201);
    }
}
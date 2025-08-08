<?php

namespace App\Features\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Order\Services\OrderQueryService;
use Illuminate\Http\Request;
use Exception;
use App\Features\Order\Enums\OrderStatus;
use App\Features\BusinessHour\Services\BusinessHourService;
use Carbon\Carbon;

class OrderReserveApiController extends Controller
{
    protected $orderQueryService;
    protected $businessHourService;

    public function __construct(OrderQueryService $orderQueryService, BusinessHourService $businessHourService)
    {
        $this->orderQueryService = $orderQueryService;
        $this->businessHourService = $businessHourService;
    }

    /**
     * Update reserve time for an unpaid order.
     *
     * @OA\Put(
     *     path="/api/orders/{orderId}/reserve-time",
     *     summary="Update reserve time for an unpaid order",
     *     tags={"Orders"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID of the order"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"reserve_time"},
     *             @OA\Property(
     *                 property="reserve_time",
     *                 type="string",
     *                 example="2025-08-05 18:30",
     *                 description="New reserve time, format: Y-m-d H:i"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Reserve time updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="order_id", type="integer", example=123),
     *             @OA\Property(property="reserve_time", type="string", example="2025-08-05 18:30")
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
    public function updateReserveTime(Request $request, $orderId)
    {
        try {
            $customerId = $this->getAuthenticatedCustomer()->id;
            $order = $this->orderQueryService->getOrderById($orderId, $customerId);

            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found'], 404);
            }

            if ($order->status != OrderStatus::Unpaid) {
                return response()->json(['success' => false, 'message' => 'Only unpaid orders can update reserve time'], 400);
            }

            $request->validate([
                'reserve_time' => 'required|date_format:Y-m-d H:i',
            ]);

            $reserveTime = Carbon::parse($request->input('reserve_time'));

            $orderType = $order->order_type;
            
            if (!$this->businessHourService->isTimeAvailableForDate($orderType, $reserveTime)) {
                return response()->json(['success' => false, 'message' => 'The reserved time is not available.'], 400);
            }

            $order->reserve_time = $reserveTime->format('Y-m-d H:i');
            $order->save();

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'reserve_time' => $order->reserve_time,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
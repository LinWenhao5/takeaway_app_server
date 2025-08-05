<?php
namespace App\Features\BusinessHour\Controllers;

use App\Http\Controllers\Controller;
use App\Features\BusinessHour\Services\BusinessHourService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Features\Order\Enums\OrderType;

class BusinessHourApiController extends Controller
{
    protected BusinessHourService $service;

    public function __construct(BusinessHourService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/business-hours/available-times",
     *     summary="Get available booking time slots for today",
     *     tags={"BusinessHour"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="order_type",
     *         in="query",
     *         required=false,
     *         description="Order type: 'delivery' for delivery (45 min interval), 'pickup' for self-pickup (30 min interval)",
     *         @OA\Schema(type="string", enum={"delivery", "pickup"}, default="delivery")
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         required=false,
     *         description="Date for available times, format: Y-m-d. Default is today.",
     *         @OA\Schema(type="string", format="date", example="2025-08-05")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with available time slots",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="success", type="boolean", example=true
     *             ),
     *             @OA\Property(
     *                 property="date", type="string", example="2025-08-05"
     *             ),
     *             @OA\Property(
     *                 property="times",
     *                 type="array",
     *                 @OA\Items(type="string", example="10:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to get available time slots",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to get available time slots."),
     *             @OA\Property(property="error", type="string", example="Some error message")
     *         )
     *     )
     * )
     */
    public function availableTimes(Request $request)
    {
        $validated = $request->validate([
            'order_type' => 'nullable|in:delivery,pickup',
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        try {
            $orderType = $validated['order_type'] ?? 'delivery';
            $date = $validated['date'] ?? now()->format('Y-m-d');

            $orderTypeEnum = OrderType::from($orderType);

            $carbonDate = Carbon::parse($date);
            $times = $this->service->getAvailableTimesForDate($orderTypeEnum, $carbonDate);

            return response()->json([
                'success' => true,
                'date' => $carbonDate->format('Y-m-d'),
                'times' => $times,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get available time slots.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
<?php
namespace App\Features\BusinessHour\Controllers;

use App\Http\Controllers\Controller;
use App\Features\BusinessHour\Services\BusinessHourService;
use Illuminate\Http\Request;
use Carbon\Carbon;


class BusinessHourApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/business-hours/available-times",
     *     summary="Get available booking time slots for today or a specific date",
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
     *         description="Date for booking, format: Y-m-d or 'tomorrow'",
     *         @OA\Schema(type="string", example="tomorrow")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with available time slots",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="date", type="string", example="2025-08-01"),
     *             @OA\Property(
     *                 property="times",
     *                 type="array",
     *                 @OA\Items(type="string", example="2025-08-01 10:00")
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
        try {
            $orderType = $request->input('order_type', 'delivery');
            $date = $request->input('date');
            $service = new BusinessHourService();

            if ($date) {
                $carbonDate = Carbon::parse($date);
                $times = $service->getAvailableTimesForDate($orderType, $carbonDate);
            } else {
                $carbonDate = now();
                $times = $service->getAvailableTimes($orderType);
            }

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
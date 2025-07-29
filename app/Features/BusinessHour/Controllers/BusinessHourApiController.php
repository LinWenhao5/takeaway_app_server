<?php
namespace App\Features\BusinessHour\Controllers;

use App\Http\Controllers\Controller;
use App\Features\BusinessHour\Models\BusinessHour;
use Carbon\Carbon;
use Illuminate\Http\Request;


class BusinessHourApiController extends Controller
{
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
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with available time slots",
     *         @OA\JsonContent(
     *             type="object",
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
        try {
            $orderType = $request->input('order_type', 'delivery');
            $advance = $orderType === 'pickup' ? 30 : 45;
            $interval = 10;

            $weekday = now()->dayOfWeek;
            $hour = BusinessHour::where('weekday', $weekday)->first();

            if (!$hour || $hour->is_closed) {
                return response()->json(['times' => []]);
            }

            $open = substr($hour->open_time, 0, 5);
            $close = substr($hour->close_time, 0, 5);

            $times = [];
            $current = Carbon::createFromFormat('H:i', $open);
            $end = Carbon::createFromFormat('H:i', $close);

            $earliest = now()->addMinutes($advance);

            while ($current < $end) {
                $slot = $current->copy()->setDate(now()->year, now()->month, now()->day);
                if ($slot->greaterThanOrEqualTo($earliest)) {
                    $times[] = $current->format('H:i');
                }
                $current->addMinutes($interval);
            }

            return response()->json([
                'success' => true,
                'times' => $times
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
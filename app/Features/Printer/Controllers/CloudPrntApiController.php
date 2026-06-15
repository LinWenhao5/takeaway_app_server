<?php
namespace App\Features\Printer\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Features\Order\Models\Order;

/**
 * @OA\Tag(name="Printer", description="CloudPRNT printer communication endpoints")
 */
class CloudPrntApiController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/cloudprnt",
     *     summary="Printer status polling and print confirmation",
     *     tags={"Printer"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"printerMAC"},
     *             @OA\Property(property="printerMAC", type="string", example="00:11:62:1a:2b:3c"),
     *             @OA\Property(property="statusCode", type="string", example="20"),
     *             @OA\Property(property="clientAction", type="string", example="applied")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operation successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="hasPrinting", type="boolean", example=true),
     *             @OA\Property(property="mediaTypes", type="array", @OA\Items(type="string", example="text/vnd.star.markup")),
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(response=429, description="Too Many Requests")
     * )
     *
     * @OA\Get(
     *     path="/api/cloudprnt",
     *     summary="Fetch the raw printing instruction text stream",
     *     tags={"Printer"},
     *     @OA\Parameter(
     *         name="mac",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="00:11:62:1a:2b:3c"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\MediaType(mediaType="text/vnd.star.markup", @OA\Schema(type="string"))
     *     ),
     *     @OA\Response(response=204, description="No content"),
     *     @OA\Response(response=400, description="Bad Request")
     * )
     */
    public function index(Request $request)
    {
        if ($request->isMethod('get')) {
            return $this->renderJob($request->query('mac'));
        }

        return $this->handlePoll($request);
    }

    private function handlePoll(Request $request)
    {
        $mac = $request->input('printerMAC');
        $clientAction = $request->input('clientAction');
        $queueKey = "printer:raw_queue:{$mac}";
        $processingKey = "printer:processing:{$mac}";

        if ($clientAction === 'applied') {
            $finishedJobJson = Redis::getdel($processingKey);
            
            if ($finishedJobJson) {
                $jobData = json_decode($finishedJobJson, true);

                if (is_array($jobData) && isset($jobData['order_id'])) {
                    Order::where('id', $jobData['order_id'])->update(['printed' => true]);
                }
            }

            return response()->json(['status' => 'success']);
        }

        $hasJob = Redis::llen($queueKey) > 0 || Redis::exists($processingKey);
        if ($hasJob) {
            return response()->json([
                'hasPrinting' => true,
                'mediaTypes' => ['text/vnd.star.markup']
            ]);
        }

        return response()->json(['hasPrinting' => false]);
    }

    private function renderJob($mac)
    {
        if (!$mac) return response('', 400);

        $queueKey = "printer:raw_queue:{$mac}";
        $processingKey = "printer:processing:{$mac}";

        $jobJson = Redis::get($processingKey);
        
        if (!$jobJson) {
            $jobJson = Redis::lpop($queueKey);
            if ($jobJson) {
                Redis::setex($processingKey, 300, $jobJson);
            }
        }

        if (!$jobJson) {
            return response('', 204);
        }

        $jobData = json_decode($jobJson, true);

        return response($jobData['markup'])
            ->header('Content-Type', 'text/vnd.star.markup');
    }
}

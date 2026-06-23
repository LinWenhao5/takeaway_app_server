<?php
namespace App\Features\Printer\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Features\Order\Models\Order;
use Illuminate\Support\Facades\Log;
use App\Features\Printer\Services\ReceiptImageGenerator;

/**
 * @OA\Tag(name="Printer", description="CloudPRNT printer communication endpoints")
 */
class CloudPrntApiController extends Controller
{
    public function index(Request $request)
    {
        $method = $request->method();
        $mac = $request->input('printerMAC') ?? $request->query('mac', 'UNKNOWN');

        Log::info("[CloudPRNT] {$method} Request Received", [
            'printer_mac' => $mac,
            'status_code' => $request->input('statusCode', 'N/A'),
            'query_params' => $request->query(),
            'is_printing' => $request->input('printingInProgress') ?? false,
        ]);

        return match ($request->method()) {
            'POST'   => $this->poll($request),
            'GET'    => $this->renderJob($request),
            'DELETE' => $this->complete($request),
            default  => response('', 405),
        };
    }

   /**
     * @OA\Post(
     *     path="/api/cloudprnt",
     *     summary="CloudPRNT printer polling",
     *     description="Printer polls server to check if a print job is available.",
     *     tags={"CloudPRNT"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"printerMAC"},
     *             @OA\Property(
     *                 property="printerMAC",
     *                 type="string",
     *                 example="00:11:62:1a:2b:3c"
     *             ),
     *             @OA\Property(
     *                 property="clientType",
     *                 type="string",
     *                 example="Star mC-Print3"
     *             ),
     *             @OA\Property(
     *                 property="statusCode",
     *                 type="integer",
     *                 example=200
     *             ),
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 example="23 86 00 00 00 00 00 00"
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Job available",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="jobReady",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="jobToken",
     *                 type="string",
     *                 example="order_123_550e8400-e29b-41d4-a716-446655440000"
     *             ),
     *             @OA\Property(
     *                 property="mediaTypes",
     *                 type="array",
     *                 @OA\Items(type="string"),
     *                 example={"text/vnd.star.markup"}
     *             )
     *         )
     *     )
     * )
     */
    private function poll(Request $request)
    {
        $mac = strtolower($request->input('printerMAC'));

        if (!$mac) {
            return response()->json(['jobReady' => false]);
        }

        $queueKey = "printer:queue:$mac";

        $jobJson = Redis::lindex($queueKey, 0);

        if (!$jobJson) {
            return response()->json(['jobReady' => false]);
        }

        $job = json_decode($jobJson, true);
        $token = $job['job_token'];

        $processingKey = "printer:processing:$mac:$token";

        if (!Redis::exists($processingKey)) {
            Redis::setex($processingKey, 120, $jobJson);
        }

        return response()->json([
            'jobReady' => true,
            'jobToken' => $token,
            'mediaTypes' => [
                'image/png'
            ],
        ]);
    }

    
    /**
     * @OA\Get(
     * path="/api/cloudprnt",
     * summary="Render print job to PNG",
     * description="Fetches order data from Redis and renders it into a PNG image stream for the Star printer.",
     * tags={"CloudPRNT"},
     * @OA\Parameter(
     * name="mac",
     * in="query",
     * required=true,
     * description="The MAC address of the printer (case-insensitive)",
     * @OA\Schema(type="string", example="00:11:62:aa:bb:cc")
     * ),
     * @OA\Parameter(
     * name="jobToken",
     * in="query",
     * required=true,
     * description="The unique token for the print job (also accepts 'token')",
     * @OA\Schema(type="string", example="tok_abc123xyz")
     * ),
     * @OA\Response(
     * response=200,
     * description="Success - Returns raw PNG binary stream",
     * @OA\Header(
     * header="Content-Type",
     * description="MIME type of the response",
     * @OA\Schema(type="string", example="image/png")
     * ),
     * @OA\Header(
     * header="X-Star-Cut",
     * description="Star CloudPRNT hardware cut command",
     * @OA\Schema(type="string", example="feed")
     * )
     * ),
     * @OA\Response(
     * response=400,
     * description="Bad Request - Missing mac or jobToken parameters"
     * ),
     * @OA\Response(
     * response=404,
     * description="Not Found - Job not found in Redis or already expired"
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal Server Error - Invalid JSON structure or missing order_data"
     * )
     * )
     */
    private function renderJob(Request $request)
    {
        $mac = strtolower($request->query('mac'));
        $jobToken = $request->query('jobToken') ?? $request->query('token');

        if (!$mac || !$jobToken) {
            return response('', 400);
        }

        $processingKey = "printer:processing:$mac:$jobToken";

        $jobJson = Redis::get($processingKey);

        if (!$jobJson) {
            return response('', 404);
        }

        $job = json_decode($jobJson, true);

        if (!is_array($job) || !isset($job['order_data'])) {
            return response('', 500);
        }

        $binary = Redis::get("printer:binary:{$mac}:{$jobToken}");

        if (!$binary) {
            $generator = new ReceiptImageGenerator();
            $binary = $generator->generate($job['order_data']);
            Redis::setex("printer:binary:{$mac}:{$jobToken}", 600, $binary);
        }

        return response($binary)
            ->header('Content-Type', 'image/png')
            ->header('X-Star-Cut', 'feed');
    }

    /**
     * @OA\Delete(
     *     path="/api/cloudprnt",
     *     summary="Complete print job",
     *     description="Printer confirms that the print job has been successfully printed.",
     *     tags={"CloudPRNT"},
     *
     *     @OA\Parameter(
     *         name="mac",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="00:11:62:1a:2b:3c"
     *     ),
     *
     *     @OA\Parameter(
     *         name="jobToken",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="order_123_550e8400-e29b-41d4-a716-446655440000"
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Print job completed"
     *     ),
     *
     *     @OA\Response(
     *         response=204,
     *         description="Job already removed"
     *     )
     * )
     */
    private function complete(Request $request)
    {
        $mac = strtolower($request->query('mac'));
        $jobToken = $request->query('jobToken') ?? $request->query('token');

        if (!$mac || !$jobToken) {
            return response('', 400);
        }

        $queueKey = "printer:queue:$mac";
        $processingKey = "printer:processing:$mac:$jobToken";

        $jobJson = Redis::getdel($processingKey);

        if (!$jobJson) {
            return response('', 204);
        }

        $job = json_decode($jobJson, true);

        if (!empty($job['order_id'])) {
            Order::where('id', $job['order_id'])
                ->update(['printed' => true]);
        }
        
        Redis::del("printer:binary:{$mac}:{$job['job_token']}");

        $list = Redis::lrange($queueKey, 0, -1);

        foreach ($list as $item) {
            $data = json_decode($item, true);

            if (($data['job_token'] ?? null) === $jobToken) {
                Redis::lrem($queueKey, 1, $item);
                break;
            }
        }

        return response('', 200);
    }
}

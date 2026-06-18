<?php
namespace App\Features\Printer\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Features\Order\Models\Order;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Tag(name="Printer", description="CloudPRNT printer communication endpoints")
 */
class CloudPrntApiController extends Controller
{
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
    public function index(Request $request)
    {
        Log::info('CloudPRNT', [
            'method' => $request->method(),
            'query'  => $request->query(),
            'body'   => $request->all(),
        ]);

        return match ($request->method()) {
            'POST'   => $this->poll($request),
            'GET'    => $this->renderJob($request),
            'DELETE' => $this->complete($request),
            default  => response('', 405),
        };
    }

    /**
     * @OA\Get(
     *     path="/api/cloudprnt",
     *     summary="Download print job",
     *     description="Printer downloads the print data for a specific job token.",
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
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         example="text/vnd.star.markup"
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Print job content",
     *         @OA\MediaType(
     *             mediaType="text/vnd.star.markup"
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Job not found"
     *     )
     * )
     */
    private function poll(Request $request)
    {
        $mac = $request->input('printerMAC');

        if (!$mac) {
            return response()->json(['jobReady' => false]);
        }

        $queueKey = "printer:queue:$mac";

        $jobJson = Redis::lindex($queueKey, 0);

        if (!$jobJson) {
            return response()->json(['jobReady' => false]);
        }

        $job = json_decode($jobJson, true);

        return response()->json([
            'jobReady' => true,
            'mediaTypes' => [
                'text/vnd.star.markup'
            ],
            'jobToken' => $job['job_token']
        ]);
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
    private function renderJob(Request $request)
    {
        $mac = $request->query('mac');
        $jobToken = $request->query('jobToken');
        $type = $request->query('type', 'text/vnd.star.markup');

        if (!$mac || !$jobToken) {
            return response('', 400);
        }

        $processingKey = "printer:processing:$mac:$jobToken";

        $jobJson = Redis::get($processingKey);

        if (!$jobJson) {
            $queueKey = "printer:queue:$mac";

            $list = Redis::lrange($queueKey, 0, -1);

            foreach ($list as $item) {
                $job = json_decode($item, true);

                if ($job['job_token'] === $jobToken) {
                    $jobJson = $item;

                    Redis::setex($processingKey, 300, $item);
                    break;
                }
            }
        }

        if (!$jobJson) {
            return response('', 404);
        }

        $job = json_decode($jobJson, true);

        return response($job['markup'])
            ->header('Content-Type', $type)
            ->header('Content-Length', strlen($job['markup']));
    }

    private function complete(Request $request)
    {
        $mac = $request->query('mac');
        $jobToken = $request->query('jobToken');

        if (!$mac || !$jobToken) {
            return response('', 400);
        }

        $processingKey = "printer:processing:$mac:$jobToken";
        $queueKey = "printer:queue:$mac";

        $jobJson = Redis::getdel($processingKey);

        if (!$jobJson) {
            return response('', 204);
        }

        $job = json_decode($jobJson, true);

        $list = Redis::lrange($queueKey, 0, -1);

        foreach ($list as $item) {
            $data = json_decode($item, true);

            if (isset($data['job_token']) && $data['job_token'] === $jobToken) {
                Redis::lrem($queueKey, 1, $item);
                break;
            }
        }

        if (!empty($job['order_id'])) {
            Order::where('id', $job['order_id'])
                ->update(['printed' => true]);
        }

        return response('', 200);
    }
}

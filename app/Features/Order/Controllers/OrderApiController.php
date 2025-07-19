<?php

namespace App\Features\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Order\Services\OrderService;
use App\Features\Order\Services\PaymentService;
use Illuminate\Http\Request;
use App\Features\Order\Events\OrderCreated;
use Exception;

class OrderApiController extends Controller
{
    protected $orderService;
    protected $paymentService;

    public function __construct(OrderService $orderService, PaymentService $paymentService)
    {
        $this->orderService = $orderService;
        $this->paymentService = $paymentService;
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
     *             required={"address_id"},
     *             @OA\Property(
     *                 property="address_id",
     *                 type="integer",
     *                 example=1,
     *                 description="ID of the address to use for this order"
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
        // Get the authenticated customer's ID
        $customerId = $this->getAuthenticatedCustomer()->id;

        // Validate the request
        $validated = $request->validate([
            'address_id' => 'required|integer|exists:addresses,id',
        ]);

        $addressId = $validated['address_id'];

        try {
            // 1. Create the order
            $order = $this->orderService->createOrder($customerId, $addressId);

            // 2. Get platform and host parameters to pass to PaymentService
            $platform = $request->input('platform');
            $host = $request->input('host');

            // 3. Create payment and get payment URL via PaymentService
            $paymentUrl = $this->paymentService->createPayment($order, $platform, $host);

            // Return order ID and payment URL
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'payment_url' => $paymentUrl,
            ]);
        } catch (Exception $e) {
            // Return error message
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }


    public function paymentWebhook(Request $request)
    {
        try {
            $paymentId = $request->id;
            $this->paymentService->handleWebhook($paymentId);
            return response()->json(['status' => 'ok']);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

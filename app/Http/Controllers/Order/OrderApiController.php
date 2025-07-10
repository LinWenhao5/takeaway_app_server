<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Exception;
use Mollie\Laravel\Facades\Mollie;
use App\Models\Order;

class OrderApiController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
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

            // 2. Create the payment and get the payment URL
            $paymentUrl = $this->orderService->createPayment($order);

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
        $paymentId = $request->id;
        if (!$paymentId) {
            return response()->json(['error' => 'No payment id'], 400);
        }

        $payment = Mollie::api()->payments->get($paymentId);

        $orderId = $payment->metadata->order_id ?? null;
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order && $payment->isPaid() && $order->status !== 'paid') {
                $order->status = 'paid';
                $order->save();
            }
        }

        return response()->json(['status' => 'ok']);
    }
}

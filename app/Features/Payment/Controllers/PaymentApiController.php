<?php
namespace App\Features\Payment\Controllers;
use App\Http\Controllers\Controller;
use App\Features\Payment\Services\PaymentService;
use Illuminate\Http\Request;
use Exception;

class PaymentApiController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
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
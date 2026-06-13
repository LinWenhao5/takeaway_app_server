<?php
namespace App\Features\Payment\Controllers;
use App\Http\Controllers\Controller;
use App\Features\Payment\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentApiController extends Controller
{
    public function __construct(protected PaymentService $paymentService){}

   public function paymentWebhook(Request $request)
    {
        $paymentId = $request->id;
        $this->paymentService->handleWebhook($paymentId);
        return response()->json(['status' => 'ok']);
    }
}
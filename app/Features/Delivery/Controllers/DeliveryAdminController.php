<?php

namespace App\Features\Delivery\Controllers;

use App\Features\Delivery\Services\DeliveryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeliveryAdminController extends Controller
{
    protected $deliveryService;

    public function __construct(DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    public function index()
    {
        $minimumAmount = $this->deliveryService->getMinimumAmount();
        $fee = $this->deliveryService->getFee();
        return view('delivery::index', compact('minimumAmount', 'fee'));
    }


    public function updateMinimumAmount(Request $request)
    {
        $request->validate([
            'minimum_amount' => 'required|numeric|min:0',
        ]);
        try {
            $this->deliveryService->setMinimumAmount($request->input('minimum_amount'));
            return redirect()->back()->with('success', __('messages.update_success'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.update_failed') . ': ' . $e->getMessage());
        }
    }

    public function updateFee(Request $request)
    {
        $request->validate([
            'fee' => 'required|numeric|min:0',
        ]);
        try {
            $this->deliveryService->setFee($request->input('fee'));
            return redirect()->back()->with('success', __('messages.update_success'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.update_failed') . ': ' . $e->getMessage());
        }
    }
}
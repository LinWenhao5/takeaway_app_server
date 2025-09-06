<?php

namespace App\Features\Delivery\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Delivery\Services\DeliveryService;

class DeliveryApiController extends Controller
{
    protected $deliveryService;

    public function __construct(DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;
    }

    /**
     * @OA\Get(
     *     path="/api/delivery/settings",
     *     summary="Get delivery fee and minimum delivery amount",
     *     tags={"Delivery"},
     *     @OA\Response(
     *         response=200,
     *         description="Successfully returns delivery fee and minimum delivery amount",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="minimum_amount", type="number", format="float", example=20.0, description="Minimum delivery amount"),
     *             @OA\Property(property="fee", type="number", format="float", example=5.0, description="Delivery fee")
     *         )
     *     )
     * )
     */
    public function getDeliverySettings()
    {
        return response()->json([
            'minimum_amount' => $this->deliveryService->getMinimumAmount(),
            'fee' => $this->deliveryService->getFee(),
        ]);
    }
}
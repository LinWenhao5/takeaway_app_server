<?php

namespace App\Features\Coupon\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Coupon\Services\CouponService;
use Illuminate\Http\Request;
use App\Features\Coupon\Resources\CouponResource;

class CouponApiController extends Controller
{
    protected CouponService $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    /**
     * @OA\Get(
     *     path="/api/coupons",
     *     summary="Get list of available coupons",
     *     tags={"Coupons"},
     *     @OA\Response(
     *         response=200, 
     *         description="Successful operation",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $data = $this->couponService->getAvailableCoupons();

        return CouponResource::collection($data);
    }

    /**
     * @OA\Post(
     * path="/api/coupons/pickup",
     * summary="Claim a coupon (User Pickup)",
     * description="Allows an authenticated customer to claim a specific coupon by its ID. It validates coupon status, stock, and customer limits.",
     * tags={"Coupons"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"coupon_id"},
     * @OA\Property(property="coupon_id", type="integer", example=1, description="The database ID of the coupon")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Coupon claimed successfully",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", example="Coupon picked up successfully"),
     * @OA\Property(
     * property="data",
     * type="object",
     * @OA\Property(property="expires_at", type="string", example="2026-07-10 23:59:59", description="Calculated precise expiration date based on coupon logic")
     * )
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated / Invalid Bearer Token",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized.")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Business Logic Error (e.g., Sold out, Limit reached, Inactive)",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="error"),
     * @OA\Property(property="message", type="string", example="This coupon has already been sold out."),
     * @OA\Property(property="error_code", type="string", example="COUPON_SOLD_OUT")
     * )
     * )
     * )
     */
    public function pickup(Request $request)
    {
        $request->validate([
            'coupon_id' => ['required', 'exists:coupons,id']
        ]);

        $customer = $this->getAuthenticatedCustomer();

        $couponId = $request->input('coupon_id');

        $this->couponService->pickup($customer->id, $couponId);

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon claimed successfully.'
        ]);
    }
}
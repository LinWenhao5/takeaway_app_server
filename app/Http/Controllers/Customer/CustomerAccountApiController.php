<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CustomerAccountApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/customer/username",
     *     summary="Get the current customer's username",
     *     tags={"Customer Account"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Username fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", example="SushiLover")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to get username",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to get username."),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function getUserName(Request $request)
    {
        try {
            $customer = $this->getAuthenticatedCustomer();
            return response()->json([
                'username' => $customer->name,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to get username.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
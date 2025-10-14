<?php
namespace App\Features\Customer\Controllers;

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

    /**
     * @OA\Post(
     *     path="/api/customer/reset-password",
     *     summary="Reset current customer's password",
     *     tags={"Customer Account"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="password", type="string", example="newpassword123", description="New password (min 8 chars)")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset successfully!")
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
     *         description="Failed to reset password",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to reset password."),
     *             @OA\Property(property="error", type="string", example="Detailed error message")
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:8',
            ]);

            $customer = $this->getAuthenticatedCustomer();
            $customer->password = $request->password;
            $customer->save();

            return response()->json([
                'message' => 'Password reset successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to reset password.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
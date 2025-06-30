<?php
namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddressApiController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/address/create",
     *     summary="Add a new address for the authenticated customer",
     *     tags={"Address"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"street","house_number","postcode","city","country"},
     *             @OA\Property(property="street", type="string", example="Damrak"),
     *             @OA\Property(property="house_number", type="string", example="1A"),
     *             @OA\Property(property="postcode", type="string", example="1012LG"),
     *             @OA\Property(property="city", type="string", example="Amsterdam"),
     *             @OA\Property(property="country", type="string", example="Netherlands")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Address created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="address", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="street", type="string", example="Damrak"),
     *                 @OA\Property(property="house_number", type="string", example="1A"),
     *                 @OA\Property(property="postcode", type="string", example="1012LG"),
     *                 @OA\Property(property="city", type="string", example="Amsterdam"),
     *                 @OA\Property(property="country", type="string", example="Netherlands"),
     *                 @OA\Property(property="customer_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to add address",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to add address"),
     *             @OA\Property(property="error", type="string", example="Exception message")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try{
            $customer = $this->getAuthenticatedCustomer();

            $validated = $request->validate([
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:20',
                'postcode' => 'required|string|max:20',
                'city' => 'required|string|max:100',
                'country' => 'required|string|max:100',
            ]);

            $address = $customer->addresses()->create($validated);

            return response()->json([
                'success' => true,
                'address' => $address,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add address',
                'error' => $e->getMessage(),
            ], 500);
        }
        
    }
}
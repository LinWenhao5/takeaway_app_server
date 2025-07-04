<?php
namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AllowedPostcode;
use Illuminate\Validation\ValidationException;
use Exception;

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
     *             required={"name","phone","street","house_number","postcode","city","country"},
     *             @OA\Property(property="name", type="string", example="John", description="Recipient name"),
     *             @OA\Property(property="phone", type="string", example="0612345678", description="Dutch phone number"),
     *             @OA\Property(property="street", type="string", example="Wagenweg"),
     *             @OA\Property(property="house_number", type="string", example="12B"),
     *             @OA\Property(property="postcode", type="string", example="1442CE"),
     *             @OA\Property(property="city", type="string", example="Purmerend"),
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
     *                 @OA\Property(property="name", type="string", example="张三"),
     *                 @OA\Property(property="phone", type="string", example="0612345678"),
     *                 @OA\Property(property="street", type="string", example="Wagenweg"),
     *                 @OA\Property(property="house_number", type="string", example="12B"),
     *                 @OA\Property(property="postcode", type="string", example="1442CE"),
     *                 @OA\Property(property="city", type="string", example="Purmerend"),
     *                 @OA\Property(property="country", type="string", example="Netherlands"),
     *                 @OA\Property(property="customer_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-01T00:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-01T00:00:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="errors", type="object")
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
        try {
            $customer = $this->getAuthenticatedCustomer();

            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'phone' => [
                    'required',
                    'regex:/^(?:\+31|0)[1-9][0-9]{8}$/'
                ],
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:20',
                'postcode' => [
                    'required',
                    'string',
                    function ($attribute, $value, $fail) {
                        if (!AllowedPostcode::isAllowed($value)) {
                            $fail('The postcode is not in the allowed range.');
                        }
                    }
                ],
                'city' => 'required|string|max:100',
                'country' => 'required|string|max:100',
            ]); 

            $address = $customer->addresses()->create($validated);

            return response()->json([
                'success' => true,
                'address' => $address,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add address',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/address",
     *     summary="Get all addresses for the authenticated customer",
     *     tags={"Address"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of addresses",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="addresses",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="张三"),
     *                     @OA\Property(property="phone", type="string", example="0612345678"),
     *                     @OA\Property(property="street", type="string", example="Wagenweg"),
     *                     @OA\Property(property="house_number", type="string", example="12B"),
     *                     @OA\Property(property="postcode", type="string", example="1442CE"),
     *                     @OA\Property(property="city", type="string", example="Purmerend"),
     *                     @OA\Property(property="country", type="string", example="Netherlands"),
     *                     @OA\Property(property="customer_id", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-01T00:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-01T00:00:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to get addresses",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to get addresses"),
     *             @OA\Property(property="error", type="string", example="Exception message")
     *         )
     *     )
     * )
     */
    public function getAddresses(Request $request)
    {
        try {
            $customer = $this->getAuthenticatedCustomer();
            $addresses = $customer->addresses()->get();

            return response()->json([
                'success' => true,
                'addresses' => $addresses,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get addresses',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
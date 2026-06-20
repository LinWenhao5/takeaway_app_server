<?php
namespace App\Features\Address\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Features\Address\Models\AllowedPostcode;
use App\Exceptions\BusinessException;

class AddressApiController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/addresses",
     *     summary="Add a new address for the authenticated customer",
     *     tags={"Addresses"},
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
     *         description="Validation failed or business logic error",
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
            ],
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ]);

        if (!AllowedPostcode::isAllowed($validated['postcode'])) {
            throw new BusinessException(
                'The postcode is not in the allowed range.',
                'POSTCODE_NOT_ALLOWED',
                422
            );
        }

        // Check if the address already exists for the customer
        $existingAddress = $customer->addresses()->where([
            ['street', $validated['street']],
            ['house_number', $validated['house_number']],
            ['postcode', $validated['postcode']],
            ['city', $validated['city']],
            ['country', $validated['country']],
        ])->first();

        if ($existingAddress) {
            throw new BusinessException(
                'This address already exists.',
                'ADDRESS_ALREADY_EXISTS',
                422
            );
        }

        $address = $customer->addresses()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Address created successfully.',
            'address' => $address,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/addresses",
     *     summary="Get all addresses for the authenticated customer",
     *     tags={"Addresses"},
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
        $customer = $this->getAuthenticatedCustomer();
        $addresses = $customer->addresses()->get();

        return response()->json([
            'success' => true,
            'addresses' => $addresses,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/addresses/{id}",
     *     summary="Update an address",
     *     tags={"Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Address ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","phone","street","house_number","postcode","city","country"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="street", type="string"),
     *             @OA\Property(property="house_number", type="string"),
     *             @OA\Property(property="postcode", type="string"),
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="country", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="address", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found"
     *     ),
     *    @OA\Response(
     *      response=422,
     *      description="Validation failed or business logic error",
    *      ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
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
            ],
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ]);

        $address = $customer->addresses()->find($id);

        if (!AllowedPostcode::isAllowed($validated['postcode'])) {
            throw new BusinessException(
                'The postcode is not in the allowed range.',
                'POSTCODE_NOT_ALLOWED',
                422
            );
        }

        if (!$address) {
            throw new BusinessException(
                'Address not found',
                'ADDRESS_NOT_FOUND',
                404
            );
        }

        $address->update($validated);

        return response()->json([
            'success' => true,
            'address' => $address,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/addresses/{id}",
     *     summary="Delete an address",
     *     tags={"Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Address ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Address deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Address not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        $customer = $this->getAuthenticatedCustomer();
        $address = $customer->addresses();

        if (!$address->find($id)) {
            throw new BusinessException(
                'Address not found',
                'ADDRESS_NOT_FOUND',
                404
            );
        }

        $address->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/addresses/allowed-postcode",
     *     summary="Check if a postcode is in the allowed range",
     *     tags={"Addresses"},
     *     @OA\Parameter(
     *         name="postcode",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         example="1442CE"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Postcode check result",
     *         @OA\JsonContent(
     *             @OA\Property(property="allowed", type="boolean", example=true)
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
     *     )
     * )
     */
    public function isAllowedPostcode(Request $request)
    {
        $validated = $request->validate([
            'postcode' => 'required|string',
        ]);
        return response()->json([
            'allowed' => AllowedPostcode::isAllowed($validated['postcode'])
        ]);
    }
}
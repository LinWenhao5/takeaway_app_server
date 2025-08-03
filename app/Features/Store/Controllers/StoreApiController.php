<?php

namespace App\Features\Store\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Store\Models\Store;

class StoreApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/store",
     *     summary="Get the store information",
     *     tags={"Store"},
     *     @OA\Response(
     *         response=200,
     *         description="Store information returned successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="street", type="string"),
     *             @OA\Property(property="house_number", type="string"),
     *             @OA\Property(property="postcode", type="string"),
     *             @OA\Property(property="city", type="string"),
     *             @OA\Property(property="country", type="string"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No store found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function showFirst()
    {
        $store = Store::first();

        if (!$store) {
            return response()->json(['message' => 'No store found'], 404);
        }

        return response()->json($store);
    }
}
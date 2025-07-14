<?php
namespace App\Features\Product\Controllers;

use App\Features\Product\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Retrieve all products",
     *     tags={"Products"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all products",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve products",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to retrieve products.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     summary="Retrieve a specific product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the product to retrieve",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to fetch product",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to fetch product.")
     *         )
     *     )
     * )
     */
    public function show(Product $product)
    {
        try {
            return $product;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch product: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products/search",
     *     summary="Search products by keyword",
     *     tags={"Products"},
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         required=true,
     *         description="Keyword to search for products",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of products matching the keyword",
     *         @OA\JsonContent(
     *             @OA\Property(property="products", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Keyword is required",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Keyword is required.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to search products",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to search products.")
     *         )
     *     )
     * )
     */
    public function search(Request $request)
    {
        try {
            $keyword = $request->input('keyword');

            if (!$keyword) {
                return response()->json(['error' => 'Keyword is required.'], 400);
            }

            $products = Product::where('name', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%")
                ->with('media')
                ->get();

            return response()->json(['products' => $products]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to search products: ' . $e->getMessage()], 500);
        }
    }
}
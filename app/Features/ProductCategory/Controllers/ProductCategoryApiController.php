<?php
namespace App\Features\ProductCategory\Controllers;

use App\Features\ProductCategory\Models\ProductCategory;
use App\Http\Controllers\Controller;

class ProductCategoryApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/product-categories",
     *     summary="Retrieve all product categories",
     *     tags={"Product Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="List of all product categories",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve product categories",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to retrieve product categories.")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return ProductCategory::orderBy('sort_order')->get();
    }

    /**
     * @OA\Get(
     *     path="/api/product-categories/full",
     *     summary="Retrieve product categories with their products",
     *     tags={"Product Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="List of product categories with their products",
     *         @OA\JsonContent(
     *             @OA\Property(property="categories", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve categories with products",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Failed to retrieve categories with products.")
     *         )
     *     )
     * )
     */
    public function categoriesWithProducts()
    {
        try {
            $cacheKey = 'categories_with_products';

            $categories = cache()->remember($cacheKey, 600, function () {
                return ProductCategory::with('products.media')
                    ->orderBy('sort_order')
                    ->get();
            });

            return response()->json(['categories' => $categories]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve categories: ' . $e->getMessage()], 500);
        }
    }
}
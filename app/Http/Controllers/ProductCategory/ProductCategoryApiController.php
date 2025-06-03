<?php
namespace App\Http\Controllers\ProductCategory;

use App\Models\ProductCategory;
use Illuminate\Routing\Controller;

class ProductCategoryApiController extends Controller
{
    public function index()
    {
        return ProductCategory::all();
    }

    public function categoriesWithProducts()
    {
        try {
            $cacheKey = 'categories_with_products';

            $categories = cache()->remember($cacheKey, 600, function () {
                return ProductCategory::with('products.media')->get();
            });

            return response()->json(['categories' => $categories]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve categories: ' . $e->getMessage()], 500);
        }
    }
}
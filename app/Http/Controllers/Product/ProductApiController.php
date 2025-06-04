<?php
namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Display the specified resource.
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
     * Search products by keyword.
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
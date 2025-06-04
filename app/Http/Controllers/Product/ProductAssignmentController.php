<?php
namespace App\Http\Controllers\Product;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductAssignmentController extends Controller
{
    /**
     * Assign a category to a product.
     */
    public function assignCategory(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'category_id' => 'required|exists:product_categories,id',
            ]);

            $product = Product::find($request->product_id);
            $product->product_category_id = $request->category_id;
            $product->save();

            return redirect()->back()->with('success', 'Category assigned successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to assign category: ' . $e->getMessage()]);
        }
    }
}
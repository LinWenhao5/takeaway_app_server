<?php
namespace App\Features\Product\Controllers;

use App\Http\Controllers\Controller;
use App\Features\Product\Models\Product;
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

            return redirect()->back()->with('success', __('product.category_assigned_success'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => __('product.category_assign_failed') . ': ' . $e->getMessage()]);
        }
    }

    /**
     * Assign a VAT rate to a product.
     */
    public function assignVat(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'vat_rate_id' => 'required|exists:vat_rates,id',
            ]);

            $product = Product::find($request->product_id);
            $product->vat_rate_id = $request->vat_rate_id;
            $product->save();

            return redirect()->back()->with('success', __('product.vat_assigned_success'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => __('product.vat_assign_failed') . ': ' . $e->getMessage()]);
        }
    }

    
}
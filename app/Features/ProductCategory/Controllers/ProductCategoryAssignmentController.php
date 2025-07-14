<?php
namespace App\Features\ProductCategory\Controllers;

use App\Features\ProductCategory\Models\ProductCategory;
use App\Features\Product\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductCategoryAssignmentController extends Controller
{
    public function assignProduct(Request $request, ProductCategory $category)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);
            $product = Product::find($request->product_id);
            $product->product_category_id = $category->id;
            $product->save();
            return redirect()->back()->with('success', 'Product assigned to category!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to assign product: ' . $e->getMessage()]);
        }
    }

    public function unassignProduct(ProductCategory $category, Product $product)
    {
        try {
            if ($product->product_category_id !== $category->id) {
                return redirect()->back()->withErrors(['error' => 'Product is not assigned to this category.']);
            }
            $product->product_category_id = null;
            $product->save();
            return redirect()->back()->with('success', 'Product unassigned successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to unassign product: ' . $e->getMessage()]);
        }
    }
}
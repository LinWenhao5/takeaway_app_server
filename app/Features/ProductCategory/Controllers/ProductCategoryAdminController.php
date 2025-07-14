<?php

namespace App\Features\ProductCategory\Controllers;

use App\Features\ProductCategory\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Media;
use App\Features\Product\Models\Product;

class ProductCategoryAdminController extends Controller
{

     public function adminIndex()
    {
        try {
            $categories = ProductCategory::orderBy('sort_order')->get();
            $products = Product::all();
            return view('productCategory::index', compact('categories', 'products'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to load categories: ' . $e->getMessage()]);
        }
    }

    public function adminEdit(string $id)
    {
        $category = ProductCategory::findOrFail($id);
        $media = Media::all();
        return view('productCategory::edit', compact('category', 'media'));
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = ProductCategory::create($request->all());

            return redirect()->route('admin.product-categories.index')->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create category: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'media_id' => 'nullable|exists:media,id',
            ]);

            $category = ProductCategory::findOrFail($id);
            $category->name = $request->input('name');
            $category->media_id = $request->input('media_id');
            $category->save();

            return redirect()->route('admin.product-categories.index')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update category: ' . $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        try {
            $category = ProductCategory::findOrFail($id);
            $category->delete();

            return redirect()->route('admin.product-categories.index')->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete category: ' . $e->getMessage()]);
        }
    }

    public function sort(Request $request)
    {
        foreach ($request->order as $item) {
            ProductCategory::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }
        cache()->forget('categories_with_products');
        return response()->json(['success' => true]);
    }

}

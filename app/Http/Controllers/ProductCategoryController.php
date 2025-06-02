<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Media;


class ProductCategoryController extends Controller
{
    public function adminIndex()
    {
        try {
            $categories = ProductCategory::all();
            $products = Product::all();
            return view('admin.categories.index', compact('categories', 'products'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to load categories: ' . $e->getMessage()]);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function adminEdit(string $id)
    {
        $category = ProductCategory::findOrFail($id);
        $media = Media::all();
        return view('admin.categories.edit', compact('category', 'media'));
    }


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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductCategory::all();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $category = ProductCategory::create($request->all());

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Category created successfully!',
                    'data' => $category
                ], 201);
            }
            return redirect()->route('admin.product-categories.index')->with('success', 'Category created successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Failed to create category: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Failed to create category: ' . $e->getMessage()]);
        }
    }

    public function categoriesWithProducts()
    {
        try {
            $categories = ProductCategory::with('products.media')->get();
            return response()->json(['categories' => $categories]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve categories: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
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

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Category updated successfully!',
                    'data' => $category
                ], 200);
            }

            return redirect()->route('admin.product-categories.index')->with('success', 'Category updated successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Failed to update category: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Failed to update category: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = ProductCategory::findOrFail($id);
            $category->delete();

            if (request()->expectsJson()) {
                return response()->json(['message' => 'Category deleted successfully!'], 200);
            }
            return redirect()->route('admin.product-categories.index')->with('success', 'Category deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Failed to delete category: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Failed to delete category: ' . $e->getMessage()]);
        }
    }
}

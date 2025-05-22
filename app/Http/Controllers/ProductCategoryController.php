<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Models\Product;

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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductCategory::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

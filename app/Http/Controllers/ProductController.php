<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Display a listing of the resource for admin.
     */
    public function adminIndex()
    {
        try {
            $products = Product::all();
            return view('admin.products.index', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to load products: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin.products.create');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to load create form: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        try {
            return view('admin.products.edit', compact('product'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to load edit form: ' . $e->getMessage()]);
        }
    }

   /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
           if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filePath = $file->store('images', 'public');
                $validation['image_url'] = asset('storage/' . $filePath);
            }

            $product = Product::create($validation);
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Product created successfully!', 'product' => $product], 201);
            }
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->withInput()->withErrors(['error' => 'File upload failed: ' . $e->getMessage()]);
        }

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validation = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:1000',
            'price' => 'sometimes|required|numeric|min:0',
            'image_url' => 'sometimes|nullable|url',
        ]);
        try {
            $product->update($validation);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Product updated successfully!', 'product' => $product], 200);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to update product: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update product: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            if (request()->expectsJson()) {
                return response()->json(['message' => 'Product deleted successfully!'], 200);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['error' => 'Failed to delete product: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }
}

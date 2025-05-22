<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

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
            $products = Product::with('media')->get();
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
            $media = Media::all();
            return view('admin.products.create', compact('media'));
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
            $media = Media::all();
            $categories = ProductCategory::all();
            $selectedMedia = $product->media->pluck('id')->toArray();
            return view('admin.products.edit', compact('product', 'media', 'selectedMedia', 'categories'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to load edit form: ' . $e->getMessage()]);
        }
    }

    public function assignCategory(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'category_id' => 'required|exists:product_categories,id',
        ]);

        $product = Product::find($request->product_id);
        $product->product_category_id = $request->category_id;
        $product->save();

        return redirect()->back()->with('success', 'Category assigned successfully!');
    }

   /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $media = $request->input('media');

        if (is_string($media)) {
            $media = array_filter(explode(',', $media));
        }

        $request->merge(['media' => $media]);
        
        $validation = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'media' => 'nullable|array',
            'media.*' => 'exists:media,id',
        ]);

        try {
            $product = Product::create($validation);

            if ($request->has('media')) {
                $product->media()->sync($request->media);
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Product created successfully!', 'product' => $product], 201);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Failed to create product: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create product: ' . $e->getMessage()]);
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
        $media = $request->input('media');

        if (is_string($media)) {
            $media = array_filter(explode(',', $media));
        }
        
        $request->merge(['media' => $media]);

        $validation = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string|max:1000',
            'price' => 'sometimes|required|numeric|min:0',
            'media' => 'nullable|array',
            'media.*' => 'exists:media,id',
        ]);
        try {
            $product->update($validation);

            if ($request->has('media')) {
                $product->media()->sync($request->media);
            }

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

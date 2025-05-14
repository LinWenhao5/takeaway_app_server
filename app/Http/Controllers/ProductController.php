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
            return response()->json($product, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
        }

        
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
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
        $product->update($validation);
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}

<?php
namespace App\Features\Product\Controllers;

use App\Features\Product\Models\Product;
use App\Models\Media;
use App\Features\ProductCategory\Models\ProductCategory;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ProductAdminController extends Controller
{
    /**
     * Display a listing of the resource for admin.
     */
    public function adminIndex()
    {
        try {
            $products = Product::with('media')->paginate(10);
            return view('product::index', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to load products: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function adminCreate()
    {
        try {
            $media = Media::all();
            return view('product::create', compact('media'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to load create form: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function adminEdit(Product $product)
    {
        try {
            $media = Media::all();
            $categories = ProductCategory::all();
            $selectedMedia = $product->media->pluck('id')->toArray();
            return view('product::edit', compact('product', 'media', 'selectedMedia', 'categories'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to load edit form: ' . $e->getMessage()]);
        }
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

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
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

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
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
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }
}
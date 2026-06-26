<?php
namespace App\Features\Product\Controllers;

use App\Features\Product\Models\Product;
use App\Features\Media\Models\Media;
use App\Features\Vat\Models\VatRate;
use App\Features\ProductCategory\Models\ProductCategory;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ProductAdminController extends Controller
{
    /**
     * Display a listing of the resource for admin.
     */
    public function adminIndex(Request $request)
    {
        try {
        $query = Product::with(['media', 'vatRate']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $allowedSorts = ['name', 'price', 'discount_price', 'is_out_of_stock'];
        $sortBy = $request->input('sort_by');
        $sortOrder = $request->input('sort_order') === 'desc' ? 'desc' : 'asc';

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate(10);

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
            $media = Media::latest()->get();
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
            if (url()->previous() !== url()->current() && str_contains(url()->previous(), route('admin.products.index'))) {
                session(['products_index_url' => url()->previous()]);
            }

            $media = Media::latest()->get();
            $categories = ProductCategory::all();
            $vats = VatRate::all();
            $selectedMedia = $product->media->pluck('id')->toArray();
            return view('product::edit', compact('product', 'media', 'selectedMedia', 'categories', 'vats'));
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
            'is_out_of_stock' => 'sometimes|boolean',
            'media' => 'nullable|array',
            'media.*' => 'exists:media,id',
            'discount_price' => 'nullable|numeric|min:0',
        ]);

        $validation['is_out_of_stock'] = $request->boolean('is_out_of_stock');

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
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'is_out_of_stock' => 'sometimes|boolean',
            'media' => 'nullable|array',
            'media.*' => 'exists:media,id',
            'discount_price' => 'nullable|numeric|min:0',
        ]);

        $validation['is_out_of_stock'] = $request->boolean('is_out_of_stock');

        try {
            $product->update($validation);

            if ($request->has('media')) {
                $product->media()->sync($request->media);
            }

            $targetUrl = session('products_index_url', route('admin.products.index'));

            session()->forget('products_index_url');

            return redirect($targetUrl)->with('success', 'Product updated successfully!');
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
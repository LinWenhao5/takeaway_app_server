<?php

use App\Http\Controllers\ProductCategory\ProductCategoryApiController;
use App\Http\Controllers\Product\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ==================== Default API Route ====================

// Get the authenticated user's information (requires Sanctum authentication)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware(['auth:sanctum', 'advancedThrottle:20,1']);

// ==================== Product API Routes ====================

// Public Product API Endpoints
Route::middleware('advancedThrottle:60,1')->group(function () {
    Route::get('/products', [ProductApiController::class, 'index'])->name('api.products.index'); // List all products
    Route::get('/products/search', [ProductApiController::class, 'search']); // Search products by name or description
    Route::get('/products/{product}', [ProductApiController::class, 'show'])->name('api.products.show'); // Show details of a single product

    // ==================== Product Category ====================
    Route::get('/product-categories', [ProductCategoryApiController::class, 'index'])->name('api.product-categories.index'); // List all product categories
    Route::get('/product-categories/full', [ProductCategoryApiController::class, 'categoriesWithProducts'])->name('api.product-categories.full'); // List all product categories with products
});
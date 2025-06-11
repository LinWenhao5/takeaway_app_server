<?php

use App\Http\Controllers\Customer\CustomerAuthController;
use App\Http\Controllers\ProductCategory\ProductCategoryApiController;
use App\Http\Controllers\Product\ProductApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware(['throttle:custom_limit'])->group(function () {
    // ==================== User API Routes ====================
    Route::prefix('user')->group(function () {
        Route::get('/', function (Request $request) {
            return $request->user();
        });
    });

    // ==================== Product API Routes ====================
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductApiController::class, 'index'])->name('api.products.index'); // List all products
        Route::get('/search', [ProductApiController::class, 'search']); // Search products by name or description
        Route::get('/{product}', [ProductApiController::class, 'show'])->name('api.products.show'); // Show details of a single product
    });

    // ==================== Product Category API Routes ====================
    Route::prefix('product-categories')->group(function () {
        Route::get('/', [ProductCategoryApiController::class, 'index'])->name('api.product-categories.index'); // List all product categories
        Route::get('/full', [ProductCategoryApiController::class, 'categoriesWithProducts'])->name('api.product-categories.full'); // List all product categories with products
    });

    // ==================== Customer API Routes ====================
    Route::prefix('customer')->group(function () {
        Route::post('/register', [CustomerAuthController::class, 'register']); // Customer registration
        Route::post('/login', [CustomerAuthController::class, 'login']);       // Customer login
        Route::post('/generate-captcha', [CustomerAuthController::class, 'generateCaptcha']); // Generate captcha
    });
});

// ==================== Authenticated Routes ====================
Route::middleware(['throttle:custom_limit', 'auth:api'])->group(function () {
    Route::get('/test', function () {
        return response()->json([
            'message' => 'Authenticated API is working!',
            'status' => 'success',
            'timestamp' => now(),
        ]);
    });
});
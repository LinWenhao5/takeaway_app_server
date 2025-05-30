<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MediaController;

// ==================== Default API Route ====================

// Get the authenticated user's information (requires Sanctum authentication)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ==================== Product API Routes ====================

// Public Product API Endpoints
Route::get('/products', [ProductController::class, 'index'])->name('api.products.index'); // List all products
Route::get('/products/search', [ProductController::class, 'search']); // Search products by name or description
Route::get('/products/{product}', [ProductController::class, 'show'])->name('api.products.show'); // Show details of a single product
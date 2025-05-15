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
Route::get('/products/{product}', [ProductController::class, 'show'])->name('api.products.show'); // Show details of a single product

// Product Management API Endpoints
Route::post('/products', [ProductController::class, 'store'])->name('api.products.store'); // Create a new product
Route::put('/products/{product}', [ProductController::class, 'update'])->name('api.products.update'); // Update an existing product
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('api.products.destroy'); // Delete a product

// ==================== Media API Routes ====================

// Media Management API Endpoints
Route::get('/media', [MediaController::class, 'index'])->name('api.media.index'); // List all media files
Route::post('/media/upload', [MediaController::class, 'upload'])->name('api.media.upload'); // Upload a new media file
Route::delete('/media/{id}', [MediaController::class, 'delete'])->name('api.media.delete'); // Delete a media file
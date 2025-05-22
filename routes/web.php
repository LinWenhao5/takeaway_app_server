<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProductCategoryController;

// ==================== Product Routes ====================

// Admin Product Management Routes
Route::get('/admin/products', [ProductController::class, 'adminIndex'])->name('admin.products.index'); // Admin: List all products
Route::get('/admin/products/create', [ProductController::class, 'adminCreate'])->name('admin.products.create'); // Admin: Show form to create a new product
Route::get('/admin/products/{product}/edit', [ProductController::class, 'adminEdit'])->name('admin.products.edit'); // Admin: Show form to edit an existing product


// Product CRUD Operations
Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store'); // Create a new product
Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update'); // Update an existing product
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy'); // Delete a product
Route::post('/admin/products/assign-category', [ProductController::class, 'assignCategory'])->name('admin.products.assignCategory'); // Assign a category to a product

// ==================== Product Category Routes ====================
Route::get('admin/product-categories', [ProductCategoryController::class, 'adminIndex'])->name('admin.product-categories.index'); // Admin: List all product categories
Route::post('/admin/categories/{category}/assign-product', [ProductCategoryController::class, 'assignProduct'])->name('admin.product-categories.assignProduct');
Route::post('admin/product-categories', [ProductCategoryController::class, 'store'])->name('admin.product-categories.store');

// ==================== Media Routes ====================

// Media Library Routes
Route::get('/media/library', [MediaController::class, 'showMediaLibrary'])->name('admin.media.library'); // Show the media library (frontend or admin)

// Media CRUD Operations
Route::get('/media', [MediaController::class, 'index'])->name('admin.media.index'); // List all media files
Route::post('/media/upload', [MediaController::class, 'upload'])->name('admin.media.upload'); // Upload a new media file
Route::delete('/media/{id}', [MediaController::class, 'delete'])->name('admin.media.delete'); // Delete a media file
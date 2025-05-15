<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MediaController;

// ==================== Default Route ====================

// The default homepage route
Route::get('/', function () {
    return view('welcome');
});

// ==================== Product Routes ====================

// Admin Product Management Routes
Route::get('/admin/products', [ProductController::class, 'adminIndex'])->name('admin.products.index'); // Admin: List all products
Route::get('/admin/products/create', [ProductController::class, 'create'])->name('admin.products.create'); // Admin: Show form to create a new product
Route::get('/admin/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit'); // Admin: Show form to edit an existing product

// Public Product Display Routes
Route::get('/products', [ProductController::class, 'index'])->name('web.products.index'); // Public: List all products
Route::get('/products/{product}', [ProductController::class, 'show'])->name('web.products.show'); // Public: Show details of a single product

// Product CRUD Operations
Route::post('/products', [ProductController::class, 'store'])->name('web.products.store'); // Create a new product
Route::put('/products/{product}', [ProductController::class, 'update'])->name('web.products.update'); // Update an existing product
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('web.products.destroy'); // Delete a product

// ==================== Media Routes ====================

// Media Library Routes
Route::get('/media/library', [MediaController::class, 'showMediaLibrary'])->name('media.library'); // Show the media library (frontend or admin)

// Media CRUD Operations
Route::get('/media', [MediaController::class, 'index'])->name('web.media.index'); // List all media files
Route::post('/media/upload', [MediaController::class, 'upload'])->name('web.media.upload'); // Upload a new media file
Route::delete('/media/{id}', [MediaController::class, 'delete'])->name('web.media.delete'); // Delete a media file
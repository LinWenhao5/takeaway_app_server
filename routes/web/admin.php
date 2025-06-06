<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\Product\ProductAdminController;
use App\Http\Controllers\Product\ProductAssignmentController;
use App\Http\Controllers\ProductCategory\ProductCategoryAdminController;
use App\Http\Controllers\ProductCategory\ProductCategoryAssignmentController;
use Illuminate\Support\Facades\Route;

// Admin Routes
Route::middleware(['auth','role:admin|owner', 'throttle:60,1'])->group(function () {
    // Product Routes
    Route::get('/admin/products', [ProductAdminController::class, 'adminIndex'])->name('admin.products.index');
    Route::get('/admin/products/create', [ProductAdminController::class, 'adminCreate'])->name('admin.products.create');
    Route::get('/admin/products/{product}/edit', [ProductAdminController::class, 'adminEdit'])->name('admin.products.edit');
    Route::post('/products', [ProductAdminController::class, 'store'])->name('admin.products.store');
    Route::put('/products/{product}', [ProductAdminController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductAdminController::class, 'destroy'])->name('admin.products.destroy');
    Route::post('/admin/products/assign-category', [ProductAssignmentController::class, 'assignCategory'])->name('admin.products.assignCategory');

    // Product Category Routes
    Route::get('admin/product-categories', [ProductCategoryAdminController::class, 'adminIndex'])->name('admin.product-categories.index');
    Route::get('admin/product-categories/{category}/edit', [ProductCategoryAdminController::class, 'adminEdit'])->name('admin.product-categories.edit');
    Route::post('/admin/categories/{category}/assign-product', [ProductCategoryAssignmentController::class, 'assignProduct'])->name('admin.product-categories.assignProduct');
    Route::delete('/admin/product-categories/{category}/unassign-product/{product}', [ProductCategoryAssignmentController::class, 'unassignProduct'])->name('admin.product-categories.unassignProduct');
    Route::post('admin/product-categories', [ProductCategoryAdminController::class, 'store'])->name('admin.product-categories.store');
    Route::delete('admin/product-categories/{category}', [ProductCategoryAdminController::class, 'destroy'])->name('admin.product-categories.destroy');
    Route::put('admin/product-categories/{category}', [ProductCategoryAdminController::class, 'update'])->name('admin.product-categories.update');

    // Media Routes
    Route::get('admin/media/library', [MediaController::class, 'showMediaLibrary'])->name('admin.media.library');
    Route::get('admin/media', [MediaController::class, 'index'])->name('admin.media.index');
    Route::post('admin/media/upload', [MediaController::class, 'upload'])->name('admin.media.upload');
    Route::delete('admin/media/{id}', [MediaController::class, 'delete'])->name('admin.media.delete');
});
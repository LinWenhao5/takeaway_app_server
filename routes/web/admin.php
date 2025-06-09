<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\Product\ProductAdminController;
use App\Http\Controllers\Product\ProductAssignmentController;
use App\Http\Controllers\ProductCategory\ProductCategoryAdminController;
use App\Http\Controllers\ProductCategory\ProductCategoryAssignmentController;
use Illuminate\Support\Facades\Route;

// ==================== Admin Routes ====================
Route::middleware(['auth:web', 'role:admin|owner', 'throttle:custom_limit'])->group(function () {

    // ==================== Product Routes ====================
    Route::prefix('admin/products')->group(function () {
        Route::get('/', [ProductAdminController::class, 'adminIndex'])->name('admin.products.index'); // List all products
        Route::get('/create', [ProductAdminController::class, 'adminCreate'])->name('admin.products.create'); // Create product form
        Route::get('/{product}/edit', [ProductAdminController::class, 'adminEdit'])->name('admin.products.edit'); // Edit product form
        Route::post('/', [ProductAdminController::class, 'store'])->name('admin.products.store'); // Store new product
        Route::put('/{product}', [ProductAdminController::class, 'update'])->name('admin.products.update'); // Update product
        Route::delete('/{product}', [ProductAdminController::class, 'destroy'])->name('admin.products.destroy'); // Delete product
        Route::post('/assign-category', [ProductAssignmentController::class, 'assignCategory'])->name('admin.products.assignCategory'); // Assign category to product
    });

    // ==================== Product Category Routes ====================
    Route::prefix('admin/product-categories')->group(function () {
        Route::get('/', [ProductCategoryAdminController::class, 'adminIndex'])->name('admin.product-categories.index'); // List all categories
        Route::get('/{category}/edit', [ProductCategoryAdminController::class, 'adminEdit'])->name('admin.product-categories.edit'); // Edit category form
        Route::post('/{category}/assign-product', [ProductCategoryAssignmentController::class, 'assignProduct'])->name('admin.product-categories.assignProduct'); // Assign product to category
        Route::delete('/{category}/unassign-product/{product}', [ProductCategoryAssignmentController::class, 'unassignProduct'])->name('admin.product-categories.unassignProduct'); // Unassign product from category
        Route::post('/', [ProductCategoryAdminController::class, 'store'])->name('admin.product-categories.store'); // Store new category
        Route::delete('/{category}', [ProductCategoryAdminController::class, 'destroy'])->name('admin.product-categories.destroy'); // Delete category
        Route::put('/{category}', [ProductCategoryAdminController::class, 'update'])->name('admin.product-categories.update'); // Update category
    });

    // ==================== Media Routes ====================
    Route::prefix('admin/media')->group(function () {
        Route::get('/library', [MediaController::class, 'showMediaLibrary'])->name('admin.media.library'); // Show media library
        Route::get('/', [MediaController::class, 'index'])->name('admin.media.index'); // List all media
        Route::post('/upload', [MediaController::class, 'upload'])->name('admin.media.upload'); // Upload media
        Route::delete('/{id}', [MediaController::class, 'delete'])->name('admin.media.delete'); // Delete media
    });
});
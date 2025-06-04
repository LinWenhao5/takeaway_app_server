<?php

use App\Http\Controllers\Auth\RegistrationInvitationController;
use App\Http\Controllers\Product\ProductAdminController;
use App\Http\Controllers\ProductCategory\ProductCategoryAdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Product\ProductAssignmentController;
use App\Http\Controllers\ProductCategory\ProductCategoryAssignmentController;

require __DIR__.'/auth.php';

// ==================== Admin Routes ====================
Route::middleware(['admin'])->group(function () {
    // ==================== Product Routes ====================
    Route::get('/admin/products', [ProductAdminController::class, 'adminIndex'])->name('admin.products.index'); // Admin: List all products
    Route::get('/admin/products/create', [ProductAdminController::class, 'adminCreate'])->name('admin.products.create'); // Admin: Show form to create a new product
    Route::get('/admin/products/{product}/edit', [ProductAdminController::class, 'adminEdit'])->name('admin.products.edit'); // Admin: Show form to edit an existing product

    // Product CRUD Operations
    Route::post('/products', [ProductAdminController::class, 'store'])->name('admin.products.store'); // Create a new product
    Route::put('/products/{product}', [ProductAdminController::class, 'update'])->name('admin.products.update'); // Update an existing product
    Route::delete('/products/{product}', [ProductAdminController::class, 'destroy'])->name('admin.products.destroy'); // Delete a product
    Route::post('/admin/products/assign-category', [ProductAssignmentController::class, 'assignCategory'])->name('admin.products.assignCategory'); // Assign a category to a product

    // ==================== Product Category Routes ====================
    Route::get('admin/product-categories', [ProductCategoryAdminController::class, 'adminIndex'])->name('admin.product-categories.index'); // Admin: List all product categories
    Route::get('admin/product-categories/{category}/edit', [ProductCategoryAdminController::class, 'adminEdit'])->name('admin.product-categories.edit'); // Show edit form

    // Product Category CRUD Operations
    Route::post('/admin/categories/{category}/assign-product', [ProductCategoryAssignmentController::class, 'assignProduct'])->name('admin.product-categories.assignProduct'); // Assign a product to a category
    Route::delete('/admin/product-categories/{category}/unassign-product/{product}', [ProductCategoryAssignmentController::class, 'unassignProduct'])->name('admin.product-categories.unassignProduct'); // Unassign a product from a category
    Route::post('admin/product-categories', [ProductCategoryAdminController::class, 'store'])->name('admin.product-categories.store');// Create a new product category
    Route::delete('admin/product-categories/{category}', [ProductCategoryAdminController::class, 'destroy'])->name('admin.product-categories.destroy');// Delete a product category
    Route::put('admin/product-categories/{category}', [ProductCategoryAdminController::class, 'update'])->name('admin.product-categories.update'); // Update category

    // ==================== Media Routes ====================
    // Media Library Routes
    Route::get('/media/library', [MediaController::class, 'showMediaLibrary'])->name('admin.media.library'); // Show the media library (frontend or admin)

    // Media CRUD Operations
    Route::get('/media', [MediaController::class, 'index'])->name('admin.media.index'); // List all media files
    Route::post('/media/upload', [MediaController::class, 'upload'])->name('admin.media.upload'); // Upload a new media file
    Route::delete('/media/{id}', [MediaController::class, 'delete'])->name('admin.media.delete'); // Delete a media file

    // ==================== Registration Invitation Routes ====================
    Route::get('/admin/invite', [RegistrationInvitationController::class, 'create'])->name('admin.invite.create');
    Route::post('/admin/invite', [RegistrationInvitationController::class, 'store'])->name('admin.invite.store');
});
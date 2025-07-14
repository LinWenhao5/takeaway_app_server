<?php

use App\Features\ProductCategory\Controllers\ProductCategoryAdminController;
use App\Features\ProductCategory\Controllers\ProductCategoryAssignmentController;
use App\Features\ProductCategory\Controllers\ProductCategoryApiController;
use Illuminate\Support\Facades\Route;

// Web Routes   
Route::middleware(['web', 'auth:web', 'role:admin|owner', 'throttle:custom_limit'])->prefix('admin/product-categories')->group(function () {
    Route::get('/', [ProductCategoryAdminController::class, 'adminIndex'])->name('admin.product-categories.index'); // List all categories
    Route::post('/admin/product-categories/sort', [ProductCategoryAdminController::class, 'sort'])->name('admin.product-categories.sort');
    Route::get('/{category}/edit', [ProductCategoryAdminController::class, 'adminEdit'])->name('admin.product-categories.edit'); // Edit category form
    Route::post('/{category}/assign-product', [ProductCategoryAssignmentController::class, 'assignProduct'])->name('admin.product-categories.assignProduct'); // Assign product to category
    Route::delete('/{category}/unassign-product/{product}', [ProductCategoryAssignmentController::class, 'unassignProduct'])->name('admin.product-categories.unassignProduct'); // Unassign product from category
    Route::post('/', [ProductCategoryAdminController::class, 'store'])->name('admin.product-categories.store'); // Store new category
    Route::delete('/{category}', [ProductCategoryAdminController::class, 'destroy'])->name('admin.product-categories.destroy'); // Delete category
    Route::put('/{category}', [ProductCategoryAdminController::class, 'update'])->name('admin.product-categories.update'); // Update category
});

// API Routes
Route::middleware(['api', 'throttle:custom_limit'])->prefix('api/product-categories')->group(function () {
    Route::get('/', [ProductCategoryApiController::class, 'index'])->name('api.product-categories.index');
    Route::get('/full', [ProductCategoryApiController::class, 'categoriesWithProducts'])->name('api.product-categories.full');
});
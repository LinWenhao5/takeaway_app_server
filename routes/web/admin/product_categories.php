<?php

use App\Http\Controllers\ProductCategory\ProductCategoryAdminController;
use App\Http\Controllers\ProductCategory\ProductCategoryAssignmentController;
use Illuminate\Support\Facades\Route;

// ==================== Admin Routes ====================
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
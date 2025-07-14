<?php
use App\Features\Product\Controllers\ProductAdminController;
use App\Features\Product\Controllers\ProductAssignmentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:web', 'role:admin|owner', 'throttle:custom_limit'])->prefix('admin/products')->group(function () {
        Route::get('/', [ProductAdminController::class, 'adminIndex'])->name('admin.products.index'); // List all products
        Route::get('/create', [ProductAdminController::class, 'adminCreate'])->name('admin.products.create'); // Create product form
        Route::get('/{product}/edit', [ProductAdminController::class, 'adminEdit'])->name('admin.products.edit'); // Edit product form
        Route::post('/', [ProductAdminController::class, 'store'])->name('admin.products.store'); // Store new product
        Route::put('/{product}', [ProductAdminController::class, 'update'])->name('admin.products.update'); // Update product
        Route::delete('/{product}', [ProductAdminController::class, 'destroy'])->name('admin.products.destroy'); // Delete product
        Route::post('/assign-category', [ProductAssignmentController::class, 'assignCategory'])->name('admin.products.assignCategory'); // Assign category to product
});
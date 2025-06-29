<?php
use App\Http\Controllers\ProductCategory\ProductCategoryApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('product-categories')->group(function () {
    Route::get('/', [ProductCategoryApiController::class, 'index'])->name('api.product-categories.index');
    Route::get('/full', [ProductCategoryApiController::class, 'categoriesWithProducts'])->name('api.product-categories.full');
});
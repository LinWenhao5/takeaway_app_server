<?php
use App\Http\Controllers\Product\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {
    Route::get('/', [ProductApiController::class, 'index'])->name('api.products.index');
    Route::get('/search', [ProductApiController::class, 'search']);
    Route::get('/{product}', [ProductApiController::class, 'show'])->name('api.products.show');
});
<?php
use App\Features\Cart\Controllers\CartApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('api/cart')->group(function () {
    Route::post('/', [CartApiController::class, 'addToCart'])->name('api.cart.add');
    Route::get('/', [CartApiController::class, 'getCart'])->name('api.cart.get');
    Route::delete('/remove', [CartApiController::class, 'removeFromCart'])->name('api.cart.remove');
    Route::delete('/remove-quantity', [CartApiController::class, 'removeQuantityFromCart'])->name('api.cart.removeQuantity');
});
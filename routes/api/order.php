<?php
use App\Http\Controllers\Order\OrderApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('order')->group(function () {
    Route::post('/', [OrderApiController::class, 'createOrder'])->name('api.orders.create');
});
<?php
use App\Features\Order\Controllers\OrderApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('orders')->group(function () {
    Route::post('/', [OrderApiController::class, 'createOrder'])->name('api.orders.create');
});


Route::prefix('orders')->group(function () {
    Route::post('/payment-webhook', [OrderApiController::class, 'paymentWebhook'])->name('orders.payment.webhook');
});
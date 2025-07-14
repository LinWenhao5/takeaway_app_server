<?php
use App\Features\Order\Controllers\OrderApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'throttle:custom_limit'])->prefix('api/orders')->group(function () {
    Route::post('/', [OrderApiController::class, 'createOrder'])
        ->middleware('auth:api')
        ->name('api.orders.create');

    Route::post('/payment-webhook', [OrderApiController::class, 'paymentWebhook'])
        ->name('orders.payment.webhook');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Order\OrderWebController;

Route::middleware('web', 'throttle:custom_limit')->group(function () {
    Route::get('/orders/payment-callback/{order}', [OrderWebController::class, 'paymentCallback'])->name('orders.payment.callback');
});
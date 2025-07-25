<?php

namespace App\Features\Payment\Controllers;
use Illuminate\Support\Facades\Route;

Route::post('api/payments/webhook', [PaymentApiController::class, 'paymentWebhook'])
    ->name('api.payment.webhook')
    ->withoutMiddleware('auth:api')
    ->middleware(['api', 'throttle:custom_limit']);
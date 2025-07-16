<?php
use App\Features\Order\Controllers\OrderApiController;
use App\Features\Order\Controllers\OrderAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'throttle:custom_limit'])->prefix('api/orders')->group(function () {
    Route::post('/', [OrderApiController::class, 'createOrder'])
        ->middleware('auth:api')
        ->name('api.orders.create');

    Route::post('/payment-webhook', [OrderApiController::class, 'paymentWebhook'])
        ->name('orders.payment.webhook');
});


Route::middleware(['web', 'auth:web', 'role:admin|owner', 'throttle:custom_limit'])
    ->prefix('admin/orders')
    ->name('admin.orders.')
    ->group(function () {
        Route::get('/', [OrderAdminController::class, 'index'])->name('index');
        Route::get('/history', [OrderAdminController::class, 'history'])->name('history');
        Route::get('/{order}', [OrderAdminController::class, 'show'])->name('show');
        Route::post('/{order}/status', [OrderAdminController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{order}', [OrderAdminController::class, 'destroy'])->name('destroy');
});
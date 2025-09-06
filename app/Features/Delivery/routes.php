<?php

use Illuminate\Support\Facades\Route;
use App\Features\Delivery\Controllers\DeliveryAdminController;
use App\Features\Delivery\Controllers\DeliveryApiController;

Route::middleware(['web', 'auth:web', 'role:admin|owner', 'throttle:custom_limit'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('delivery', [DeliveryAdminController::class, 'index'])->name('delivery.index');
    Route::post('delivery/minimum-amount', [DeliveryAdminController::class, 'updateMinimumAmount'])->name('delivery.updateMinimumAmount');
    Route::post('delivery/fee', [DeliveryAdminController::class, 'updateFee'])->name('delivery.updateFee');
});


Route::middleware(['api', 'throttle:custom_limit'])
    ->prefix('api')
    ->group(function () {
        Route::get('/delivery/settings', [DeliveryApiController::class, 'getDeliverySettings']);
});
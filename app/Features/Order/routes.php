<?php
use App\Features\Order\Controllers\OrderApiController;
use App\Features\Order\Controllers\OrderAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:api', 'throttle:custom_limit'])
    ->prefix('api/orders')
    ->group(function () {
        Route::post('/', [OrderApiController::class, 'createOrder'])->name('api.orders.create');
        Route::post('/{order}/repay', [OrderApiController::class, 'repayOrder'])->name('api.orders.repay');
        Route::get('/{order}/status', [OrderApiController::class, 'getOrderStatus'])->name('api.orders.status');
        Route::get('/{order}', [OrderApiController::class, 'getOrderDetail'])->name('api.orders.show');
        Route::get('/', [OrderApiController::class, 'getOrdersByCustomerId'])->name('api.orders.list');
        Route::put('/{order}/reserve-time', [OrderApiController::class, 'updateReserveTime'])->name('api.orders.updateReserveTime');
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
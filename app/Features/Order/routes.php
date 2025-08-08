<?php
use App\Features\Order\Controllers\OrderQueryApiController;
use App\Features\Order\Controllers\OrderAdminController;
use Illuminate\Support\Facades\Route;
use App\Features\Order\Controllers\OrderReserveApiController;
use App\Features\Order\Controllers\OrderPaymentApiController;

Route::middleware(['api', 'auth:api', 'throttle:custom_limit'])
    ->prefix('api/orders')
    ->group(function () {
        Route::post('/', [OrderPaymentApiController::class, 'createOrder'])->name('api.orders.create');
        Route::post('/{order}/repay', [OrderPaymentApiController::class, 'repayOrder'])->name('api.orders.repay');
        Route::get('/{order}/status', [OrderQueryApiController::class, 'getOrderStatus'])->name('api.orders.status');
        Route::get('/{order}', [OrderQueryApiController::class, 'getOrderDetail'])->name('api.orders.show');
        Route::get('/', [OrderQueryApiController::class, 'getOrdersByCustomerId'])->name('api.orders.list');
        Route::put('/{order}/reserve-time', [OrderReserveApiController::class, 'updateReserveTime'])->name('api.orders.updateReserveTime');
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
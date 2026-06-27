<?php
namespace App\Features\Coupon;
use Illuminate\Support\Facades\Route;
use App\Features\Coupon\Controllers\CouponAdminController;
use App\Features\Coupon\Controllers\CouponApiController;

Route::middleware(['api', 'throttle:custom_limit'])->prefix('api/coupons')->group(function () {
    Route::get('/', [CouponApiController::class, 'index']);
    Route::middleware(['auth:api'])->group(function () {
        Route::post('/pickup', [CouponApiController::class, 'pickup']);
        Route::get('/my-coupons', [CouponApiController::class, 'getCouponByCustomer']);
    });
});


Route::middleware(['web', 'auth:web', 'role:owner', 'throttle:custom_limit'])->prefix('admin/coupon')
->group(function () {
    Route::get('/', [CouponAdminController::class, 'index'])->name('admin.coupons.index');
    Route::get('/create', [CouponAdminController::class, 'create'])->name('admin.coupons.create');
    Route::post('/', [CouponAdminController::class, 'store'])->name('admin.coupons.store');
    Route::get('/{coupon}/edit', [CouponAdminController::class, 'edit'])->name('admin.coupons.edit');
    Route::put('/{coupon}', [CouponAdminController::class, 'update'])->name('admin.coupons.update');
    Route::delete('/{coupon}', [CouponAdminController::class, 'destroy'])->name('admin.coupons.destroy'); 
});
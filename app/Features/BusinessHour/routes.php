<?php
use Illuminate\Support\Facades\Route;
use App\Features\BusinessHour\Controllers\BusinessHourAdminController;
use App\Features\BusinessHour\Controllers\BusinessHourApiController;

Route::middleware(['web', 'auth:web', 'role:admin|owner', 'throttle:custom_limit'])
    ->prefix('admin/business-hours')
    ->name('admin.business-hours.')
    ->group(function () {
        Route::get('/', [BusinessHourAdminController::class, 'index'])->name('index');
        Route::put('{id}/time', [BusinessHourAdminController::class, 'updateTime'])->name('update-time');
        Route::put('{id}/closed', [BusinessHourAdminController::class, 'updateClosed'])->name('update-closed');
    });

Route::middleware(['api', 'auth:api', 'throttle:custom_limit'])
    ->prefix('api/business-hours')
    ->name('api.business-hours.')
    ->group(function () {
        Route::get('/available-times', [BusinessHourApiController::class, 'availableTimes'])->name('available-times');
    });
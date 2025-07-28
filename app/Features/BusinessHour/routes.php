<?php
use Illuminate\Support\Facades\Route;
use App\Features\BusinessHour\Controllers\BusinessHourAdminController;

Route::middleware(['web', 'auth:web', 'role:admin|owner', 'throttle:custom_limit'])->prefix('admin/business-hours')->name('admin.business-hours.')->group(function () {
    Route::get('/', [BusinessHourAdminController::class, 'index'])->name('index');
    Route::put('/{id}', [BusinessHourAdminController::class, 'update'])->name('update');
});
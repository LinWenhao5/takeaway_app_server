<?php

use App\Features\Address\Controllers\AllowedPostcodeAdminController;
use App\Features\Address\Controllers\AddressApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:web', 'role:admin|owner', 'throttle:custom_limit'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('allowed-postcodes', AllowedPostcodeAdminController::class);
});

Route::middleware(['api', 'auth:api'])->prefix('api/addresses')->group(function () {
    Route::post('/', [AddressApiController::class, 'store']);
    Route::get('/', [AddressApiController::class, 'getAddresses']);
    Route::put('/{id}', [AddressApiController::class, 'update']);
    Route::delete('/{id}', [AddressApiController::class, 'destroy']);
});
<?php

use App\Http\Controllers\Address\AddressApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('address')->group(function () {
    Route::post('/', [AddressApiController::class, 'store']);
    Route::get('/', [AddressApiController::class, 'getAddresses']);
    Route::put('/{id}', [AddressApiController::class, 'update']);
    Route::delete('/{id}', [AddressApiController::class, 'destroy']);
});
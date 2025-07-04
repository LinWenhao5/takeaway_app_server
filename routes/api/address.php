<?php

use App\Http\Controllers\Address\AddressApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('address')->group(function () {
    Route::post('/create', [AddressApiController::class, 'store']);
    Route::get('/', [AddressApiController::class, 'getAddresses']);
});
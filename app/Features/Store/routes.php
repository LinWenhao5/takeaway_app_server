<?php
use Illuminate\Support\Facades\Route;
use App\Features\Store\Controllers\StoreApiController;    

Route::middleware(['api', 'throttle:custom_limit'])->prefix('api/store')->group(function () {
    Route::get('/', [StoreApiController::class, 'showFirst'])->name('api.store.showFirst');
});
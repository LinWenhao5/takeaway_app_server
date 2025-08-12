<?php
use App\Features\Customer\Controllers\CustomerAuthApiController;
use App\Features\Customer\Controllers\CustomerAccountApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'throttle:custom_limit'])->prefix('api/customer')->group(function () {
    Route::post('/login', [CustomerAuthApiController::class, 'login']);
    Route::post('/register', [CustomerAuthApiController::class, 'register']);
    Route::post('/reset-password', [CustomerAuthApiController::class, 'resetPassword']);
    Route::post('/generate-captcha', [CustomerAuthApiController::class, 'generateCaptcha']);

    Route::middleware(['auth:api'])->group(function () {
        Route::get('/username', [CustomerAccountApiController::class, 'getUserName']);
    });
});
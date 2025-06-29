<?php
use App\Http\Controllers\Customer\CustomerAuthApiController;
use App\Http\Controllers\Customer\CustomerAccountApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function () {
    Route::post('/login', [CustomerAuthApiController::class, 'login']);
    Route::post('/register', [CustomerAuthApiController::class, 'register']);
    Route::post('/generate-captcha', [CustomerAuthApiController::class, 'generateCaptcha']);
});

Route::middleware(['auth:api'])->prefix('customer')->group(function () {
    Route::get('/username', [CustomerAccountApiController::class, 'getUserName']);
});
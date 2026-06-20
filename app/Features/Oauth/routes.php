<?php
use Illuminate\Support\Facades\Route;
use App\Features\Oauth\Controllers\GoogleOAuthController;

Route::middleware(['web', 'throttle:custom_limit'])->prefix('/auth/google')->group(function () {
    Route::get('/redirect', [GoogleOAuthController::class, 'redirect']);
    Route::get('/callback', [GoogleOAuthController::class, 'callback']);
});
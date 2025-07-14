<?php
use Illuminate\Support\Facades\Route;
use App\Features\Auth\Controllers\AuthController;
use App\Http\Controllers\User\RegistrationInvitationController;

Route::middleware('web', 'throttle:custom_limit')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::get('/register', [RegistrationInvitationController::class, 'register'])->name('admin.invite.register');
    Route::post('/register', [RegistrationInvitationController::class, 'completeRegistration'])->name('admin.invite.complete');
});
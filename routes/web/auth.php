<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\RegistrationInvitationController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [RegistrationInvitationController::class, 'register'])->name('invite.register');
Route::post('/register', [RegistrationInvitationController::class, 'completeRegistration'])->name('invite.complete');
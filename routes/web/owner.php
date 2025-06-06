<?php

use App\Http\Controllers\User\RegistrationInvitationController;
use App\Http\Controllers\User\UserAdminController;
use Illuminate\Support\Facades\Route;

// Owner Routes
Route::middleware(['auth', 'role:owner', 'throttle:60,1'])->group(function () {
    // User Routes
    Route::get('/admin/users', [UserAdminController::class, 'adminIndex'])->name('admin.users.index');
    Route::get('/admin/invite', [RegistrationInvitationController::class, 'create'])->name('admin.invite.create');
    Route::post('/admin/invite', [RegistrationInvitationController::class, 'store'])->name('admin.invite.store');
    Route::delete('/admin/invite/cancel/{invitation}', [RegistrationInvitationController::class, 'cancel'])->name('admin.invite.cancel');
    Route::delete('/admin/users/{user}', [UserAdminController::class, 'destroy'])->name('admin.users.destroy');
});
<?php

use App\Http\Controllers\User\RegistrationInvitationController;
use App\Http\Controllers\User\UserAdminController;
use Illuminate\Support\Facades\Route;

// ==================== Owner Routes ====================
Route::middleware(['auth:web', 'role:owner', 'throttle:custom_limit'])->group(function () {

    // ==================== User Management Routes ====================
    Route::prefix('admin/users')->group(function () {
        Route::get('/', [UserAdminController::class, 'adminIndex'])->name('admin.users.index'); // List all users
        Route::delete('/{user}', [UserAdminController::class, 'destroy'])->name('admin.users.destroy'); // Delete a user
    });

    // ==================== Registration Invitation Routes ====================
    Route::prefix('admin/invite')->group(function () {
        Route::get('/', [RegistrationInvitationController::class, 'create'])->name('admin.invite.create'); // Create invitation form
        Route::post('/', [RegistrationInvitationController::class, 'store'])->name('admin.invite.store'); // Store new invitation
        Route::delete('/cancel/{invitation}', [RegistrationInvitationController::class, 'cancel'])->name('admin.invite.cancel'); // Cancel invitation
    });
});
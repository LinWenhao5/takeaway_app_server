<?php

use App\Http\Controllers\User\RegistrationInvitationController;
use Illuminate\Support\Facades\Route;

// ==================== Owner Routes ====================
Route::middleware(['web', 'auth:web', 'role:owner', 'throttle:custom_limit'])->prefix('admin/invite')->group(function () {
    Route::get('/', [RegistrationInvitationController::class, 'create'])->name('admin.invite.create'); // Create invitation form
    Route::post('/', [RegistrationInvitationController::class, 'store'])->name('admin.invite.store'); // Store new invitation
    Route::delete('/cancel/{invitation}', [RegistrationInvitationController::class, 'cancel'])->name('admin.invite.cancel'); // Cancel invitation
});
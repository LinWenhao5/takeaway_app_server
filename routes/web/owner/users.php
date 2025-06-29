<?php

use App\Http\Controllers\User\UserAdminController;
use Illuminate\Support\Facades\Route;

// ==================== Owner Routes ====================
Route::middleware(['web', 'auth:web', 'role:owner', 'throttle:custom_limit'])->prefix('admin/users')->group(function () {
    Route::get('/', [UserAdminController::class, 'adminIndex'])->name('admin.users.index'); // List all users
    Route::delete('/{user}', [UserAdminController::class, 'destroy'])->name('admin.users.destroy'); // Delete a user
});
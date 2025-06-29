<?php

use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth:web', 'role:admin|owner', 'throttle:custom_limit'])->prefix('admin/media')->group(function () {
    Route::get('/library', [MediaController::class, 'showMediaLibrary'])->name('admin.media.library'); // Show media library
    Route::get('/', [MediaController::class, 'index'])->name('admin.media.index'); // List all media
    Route::post('/upload', [MediaController::class, 'upload'])->name('admin.media.upload'); // Upload media
    Route::delete('/{id}', [MediaController::class, 'delete'])->name('admin.media.delete'); // Delete media
});
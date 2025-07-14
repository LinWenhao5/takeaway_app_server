<?php
use Illuminate\Support\Facades\Route;
use App\Features\Setting\Controllers\SettingsController;


// ==================== Settings Routes ====================
Route::middleware(['web', 'throttle:custom_limit'])->prefix('admin/settings')->group(function () {
    Route::get('/set-locale/{locale}', [SettingsController::class, 'setLocale'])->name('set.locale');
    Route::get('/set-theme/{theme}', [SettingsController::class, 'setTheme'])->name('set.theme');

    Route::middleware(['auth:web'])->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('admin.settings.index');
    });
});


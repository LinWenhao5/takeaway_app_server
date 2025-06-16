<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Cookie;


// ==================== Settings Routes ====================
Route::prefix('admin/settings')->middleware(['throttle:custom_limit'])->group(function () {
    Route::get('/set-locale/{locale}', [SettingsController::class, 'setLocale'])->name('set.locale');
    Route::get('/set-theme/{theme}', [SettingsController::class, 'setTheme'])->name('set.theme');

    Route::middleware(['auth:web'])->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('admin.settings.index');
    });
});


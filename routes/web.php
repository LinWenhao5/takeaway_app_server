<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Cookie;

require __DIR__.'/web/auth.php';
require __DIR__.'/web/admin.php';
require __DIR__.'/web/owner.php';

Route::get('/', [WelcomeController::class, 'index'])->middleware('throttle:custom_limit');


Route::get('/set-locale/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'zh-cn'])) {
        Cookie::queue('locale', $locale, 60 * 24 * 365);
    }
    return redirect()->back();
})->name('set.locale');
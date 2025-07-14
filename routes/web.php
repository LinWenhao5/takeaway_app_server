<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;

require __DIR__.'/web/settings.php';

// Admin Routes
require __DIR__.'/web/admin/media.php';
require __DIR__.'/web/admin/allowed_postcodes.php';

// Owner Routes
require __DIR__.'/web/owner/users.php';
require __DIR__.'/web/owner/users_invite.php';

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->middleware('throttle:custom_limit');


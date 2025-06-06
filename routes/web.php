<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;

require __DIR__.'/web/auth.php';
require __DIR__.'/web/admin.php';
require __DIR__.'/web/owner.php';

Route::get('/', [WelcomeController::class, 'index'])->middleware('throttle:10,1');
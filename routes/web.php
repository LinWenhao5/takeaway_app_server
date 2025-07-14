<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;

require __DIR__.'/web/settings.php';


// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->middleware('throttle:custom_limit');


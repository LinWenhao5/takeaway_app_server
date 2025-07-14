<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;


// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->middleware('throttle:custom_limit');


<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->prefix('user')->group(function () {
    Route::get('/', function (Request $request) {
        return $request->user();
    });
});
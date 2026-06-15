<?php

use Illuminate\Support\Facades\Route;
use App\Features\Printer\Controllers\CloudPrntApiController;

Route::match(['get', 'post'], 'api/cloudprnt', [CloudPrntApiController::class, 'index'])
    ->name('api.printer.webhook')
    ->withoutMiddleware('auth:api')
    ->middleware(['api', 'throttle:custom_limit']);
<?php

use Illuminate\Support\Facades\Route;
use App\Features\Printer\Controllers\CloudPrntApiController;
use App\Features\Printer\Controllers\PrinterAdminController;

Route::match(['get', 'post'], 'api/cloudprnt', [CloudPrntApiController::class, 'index'])
    ->name('api.printer.webhook')
    ->withoutMiddleware('auth:api')
    ->middleware(['api', 'throttle:custom_limit']);


Route::middleware(['web', 'auth:web', 'role:admin|owner', 'throttle:custom_limit'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('printers', [PrinterAdminController::class, 'index'])->name('printers.index');
        Route::get('printers/create', [PrinterAdminController::class, 'create'])->name('printers.create');
        Route::post('printers', [PrinterAdminController::class, 'store'])->name('printers.store');
        Route::get('printers/{printer}/edit', [PrinterAdminController::class, 'edit'])->name('printers.edit');
        Route::put('printers/{printer}', [PrinterAdminController::class, 'update'])->name('printers.update');
        Route::delete('printers/{printer}', [PrinterAdminController::class, 'destroy'])->name('printers.destroy');
    });
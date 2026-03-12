<?php
use Illuminate\Support\Facades\Route;
use App\Features\Vat\Controllers\VatAdminController;

Route::middleware(['web', 'auth:web', 'role:owner', 'throttle:custom_limit'])->prefix('admin/vat')
	->group(function () {
		Route::get('/', [VatAdminController::class, 'adminIndex'])->name('admin.vat.index');
		Route::get('/create', [VatAdminController::class, 'adminCreate'])->name('admin.vat.create');
		Route::post('/', [VatAdminController::class, 'adminStore'])->name('admin.vat.store');
		Route::get('/{vat}/edit', [VatAdminController::class, 'adminEdit'])->name('admin.vat.edit');
		Route::put('/{vat}', [VatAdminController::class, 'adminUpdate'])->name('admin.vat.update');
		Route::delete('/{vat}', [VatAdminController::class, 'adminDestroy'])->name('admin.vat.destroy');
	});

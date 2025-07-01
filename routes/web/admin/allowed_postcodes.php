<?php

use App\Http\Controllers\Address\AllowedPostcodeAdminController;

Route::middleware(['web', 'auth:web', 'role:admin|owner', 'throttle:custom_limit'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('allowed-postcodes', AllowedPostcodeAdminController::class);
});
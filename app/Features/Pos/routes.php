<?php
use App\Features\Pos\Views\Components\PosTerminal;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'role:admin|owner', 'auth:web'])
->group(function () {
    Route::get('/pos', PosTerminal::class)->name('pos.terminal');
});
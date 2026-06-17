<?php
namespace App\Features\Printer\Providers;

use Illuminate\Support\ServiceProvider;

class PrinterServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/Printer/Views'), 'printer');
    }
}
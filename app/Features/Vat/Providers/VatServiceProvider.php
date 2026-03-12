<?php
namespace App\Features\Vat\Providers;

use Illuminate\Support\ServiceProvider;

class VatServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/Vat/Views'), 'vat');
    }
}
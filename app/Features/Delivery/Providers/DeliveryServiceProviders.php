<?php
namespace App\Features\Delivery\Providers;
use Illuminate\Support\ServiceProvider;

class DeliveryServiceProviders extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/Delivery/Views'), 'delivery');
    }
}
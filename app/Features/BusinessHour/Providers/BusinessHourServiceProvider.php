<?php

namespace App\Features\BusinessHour\Providers;

use Illuminate\Support\ServiceProvider;

class BusinessHourServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/BusinessHour/Views'), 'business_hour');
    }
}
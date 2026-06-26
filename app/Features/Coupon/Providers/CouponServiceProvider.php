<?php

namespace App\Features\Coupon\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class CouponServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/Coupon/Views'), 'coupon');
        Blade::componentNamespace('App\\Features\\Coupon\\Views\\components', 'coupon');
    }
}
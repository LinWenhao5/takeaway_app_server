<?php

namespace App\Features\Address\Providers;

use Illuminate\Support\ServiceProvider;

class AddressServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/Address/Views'), 'allowed_postcodes');
    }
}
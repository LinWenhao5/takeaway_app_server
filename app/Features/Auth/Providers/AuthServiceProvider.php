<?php

namespace App\Features\Auth\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/Auth/Views'), 'auth');
    }
}
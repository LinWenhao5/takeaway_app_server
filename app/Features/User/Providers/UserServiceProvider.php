<?php

namespace App\Features\User\Providers;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/User/Views'), 'user');
    }
}
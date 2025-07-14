<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        foreach (glob(app_path('Features/*/routes.php')) as $routeFile) {
            $this->loadRoutesFrom($routeFile);
        }
    }
}
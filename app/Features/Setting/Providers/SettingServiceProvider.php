<?php
namespace App\Features\Setting\Providers;
use Illuminate\Support\ServiceProvider;

class SettingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/Setting/Views'), 'setting');
    }
}
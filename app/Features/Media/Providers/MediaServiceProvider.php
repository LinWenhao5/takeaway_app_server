<?php

namespace App\Features\Media\Providers;

use Illuminate\Support\ServiceProvider;

class MediaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/Media/Views'), 'media');
    }
}
<?php

namespace App\Features\Order\Providers;

use Illuminate\Support\ServiceProvider;
use App\Features\Order\Events\OrderCreated;
use App\Features\Order\Listeners\SendOrderToPrinterListener;
use Illuminate\Support\Facades\Event;


class OrderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/Order/Views'), 'order');

        Event::listen(
            OrderCreated::class,
            SendOrderToPrinterListener::class
        );
    }
}
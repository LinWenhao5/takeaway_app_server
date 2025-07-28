<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Features\Product\Providers\ProductServiceProvider::class,
    App\Features\Auth\Providers\AuthServiceProvider::class,
    App\Features\ProductCategory\Providers\ProductCategoryServiceProvider::class,
    App\Features\Media\Providers\MediaServiceProvider::class,
    App\Features\User\Providers\UserServiceProvider::class,
    App\Features\Address\Providers\AddressServiceProvider::class,
    App\Features\Setting\Providers\SettingServiceProvider::class,
    App\Features\Order\Providers\OrderServiceProvider::class,
    App\Features\BusinessHour\Providers\BusinessHourServiceProvider::class,
];

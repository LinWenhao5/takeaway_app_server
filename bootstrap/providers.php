<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Features\Product\Providers\ProductServiceProvider::class,
    App\Features\Auth\Providers\AuthServiceProvider::class,
    App\Features\ProductCategory\Providers\ProductCategoryServiceProvider::class,
    App\Features\Media\Providers\MediaServiceProvider::class
];

<?php
namespace App\Features\ProductCategory\Providers;

use Illuminate\Support\ServiceProvider;

class ProductCategoryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(app_path('Features/ProductCategory/Views'), 'productCategory');
    }
}
<?php

namespace App\Features\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Features\Media\Models\Media;
use App\Features\ProductCategory\Models\ProductCategory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'product_category_id',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'media_product');
    }
    

    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            cache()->forget('categories_with_products');
        });

        static::deleted(function () {
            cache()->forget('categories_with_products');
        });
    }
}

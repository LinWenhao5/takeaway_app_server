<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = ['name'];

    /**
     * The products that belong to the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
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

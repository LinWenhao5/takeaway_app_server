<?php

namespace App\Features\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Features\Media\Models\Media;
use App\Features\ProductCategory\Models\ProductCategory;
use App\Features\Vat\Models\VatRate;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'discount_price',
        'is_out_of_stock',
        'product_category_id',
        'vat_rate_id',
    ];

     protected $appends = [
        'final_price',
        'is_discounted',
    ];

    protected $casts = [
        'is_out_of_stock' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    public function getIsDiscountedAttribute()
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function vatRate()
    {
        return $this->belongsTo(VatRate::class, 'vat_rate_id');
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

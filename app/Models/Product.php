<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'product_category_id',
    ];

    public function media()
    {
        return $this->belongsToMany(Media::class, 'media_product');
    }
}

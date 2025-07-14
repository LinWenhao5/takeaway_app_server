<?php
namespace App\Features\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Features\Product\Models\Product;


class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'public_id',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'media_product');
    }
}

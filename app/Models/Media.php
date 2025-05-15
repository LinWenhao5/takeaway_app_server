<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'media_product');
    }
}

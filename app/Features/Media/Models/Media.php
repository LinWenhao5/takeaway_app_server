<?php
namespace App\Features\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Features\Product\Models\Product;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'public_id',
    ];

    protected $appends = ['optimized_url'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'media_product');
    }

    protected function optimizedUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (blank($this->path)) {
                    return '';
                }

                // 如果包含 Cloudinary 的路径，动态插入压缩参数
                if (str_contains($this->path, '/image/upload/')) {
                    return str_replace('/image/upload/', '/image/upload/f_auto,q_auto/', $this->path);
                }

                return $this->path;
            }
        );
    }
}

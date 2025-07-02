<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
        'total_price',
        'address_id',
        'address_snapshot',
    ];

    protected $casts = [
        'address_snapshot' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity', 'price');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}
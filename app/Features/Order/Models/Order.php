<?php

namespace App\Features\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Features\Customer\Models\Customer;
use App\Features\Product\Models\Product;
use App\Features\Address\Models\Address;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
        'payment_id',
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
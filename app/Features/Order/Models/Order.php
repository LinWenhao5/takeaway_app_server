<?php

namespace App\Features\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Features\Order\Enums\OrderStatus;
use App\Features\Order\Enums\OrderType;
use App\Features\Customer\Models\Customer;
use App\Features\Product\Models\Product;
use App\Features\Address\Models\Address;
use App\Features\Coupon\Models\Coupon;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'products_snapshot',
        'status',
        'payment_id',
        'total_price',
        'delivery_fee',
        'address_id',
        'address_snapshot',
        'vat_snapshot',
        'order_type',
        'reserve_time',
        'total_vat_amount',
        'note',
        'printed',
        'order_date',
        'daily_sequence',
        'coupon_id',
        'coupon_discount_amount',
        'coupon_snapshot'
    ];

    protected $casts = [
        'reserve_time' => 'datetime',
        'address_snapshot' => 'array',
        'vat_snapshot' => 'array',
        'status' => OrderStatus::class,
        'order_type' => OrderType::class,  
        'products_snapshot' => 'array',
        'printed' => 'boolean',
        'coupon_snapshot' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($order) {
            $order->public_id = (string) Str::ulid();
        });
    }
        

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity', 'price', 'final_price')
                    ->withTimestamps();
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coupon()
{
    return $this->belongsTo(Coupon::class);
}
}
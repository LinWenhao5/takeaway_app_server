<?php

namespace App\Features\Coupon\Models;

use Illuminate\Database\Eloquent\Model;
use App\Features\Customer\Models\Customer;

class Coupon extends Model
{
    protected $fillable = [
        'name', 'code', 'type', 'value', 'min_order_amount',
        'pickup_start_at', 'pickup_end_at', 'valid_days',
        'use_start_at', 'use_end_at', 'total_quantity',
        'received_quantity', 'per_customer_limit', 'is_active'
    ];

    protected $casts = [
        'pickup_start_at' => 'datetime',
        'pickup_end_at' => 'datetime',
        'use_start_at' => 'datetime',
        'use_end_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'coupon_customer')
                    ->withPivot('id', 'received_at', 'expires_at', 'is_used', 'used_at', 'order_id')
                    ->withTimestamps();
    }
}
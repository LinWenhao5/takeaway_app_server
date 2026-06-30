<?php

namespace App\Features\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Features\Order\Models\Order;
use App\Features\Address\Models\Address;
use App\Features\Coupon\Models\Coupon;

class Customer extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id'
    ];

    protected $hidden = [
        'password',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_customer', 'customer_id', 'coupon_id')
                ->withPivot(['id', 'is_used', 'expires_at', 'received_at', 'used_at'])
                ->withTimestamps();
    }
}
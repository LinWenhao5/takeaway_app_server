<?php

namespace App\Features\Address\Models;
use App\Features\Customer\Models\Customer;
use App\Features\Order\Models\Order;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'phone',
        'street',
        'house_number',
        'postcode',
        'city',
        'country',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

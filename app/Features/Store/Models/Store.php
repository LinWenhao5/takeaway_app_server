<?php
namespace App\Features\Store\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';

    protected $fillable = [
        'name',
        'phone',
        'street',
        'house_number',
        'postcode',
        'city',
        'country',
    ];
}
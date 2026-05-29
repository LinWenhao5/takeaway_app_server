<?php
namespace App\Features\BusinessHour\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $table = 'business_hours';

    public $timestamps = false;

    protected $fillable = [
        'weekday',
        'open_time',
        'close_time',
        'is_closed',
        'is_delivery_closed',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
        'is_delivery_closed' => 'boolean',
    ];
}
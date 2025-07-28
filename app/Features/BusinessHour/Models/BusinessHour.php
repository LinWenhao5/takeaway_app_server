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
    ];

    protected $casts = [
        'is_closed' => 'boolean',
    ];
}
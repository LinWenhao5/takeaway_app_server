<?php

namespace App\Features\Printer\Models;

use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    protected $fillable = [
        'mac_address',
        'name',
        'is_online',
    ];


    protected $casts = [
        'is_online' => 'boolean',
    ];
}
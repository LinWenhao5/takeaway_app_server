<?php

namespace App\Features\Vat\Models;

use Illuminate\Database\Eloquent\Model;

class VatRate extends Model
{
    protected $fillable = [
        'name',
        'rate',
    ];
}
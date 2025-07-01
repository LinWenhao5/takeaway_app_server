<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedPostcode extends Model
{
    protected $fillable = [
        'postcode_pattern',
    ];

    /**
     * Check if the postcode is allowed.
     *
     * @param string $postcode
     * @return bool
     */
    public static function isAllowed($postcode)
    {
        if (!preg_match('/^\d{4}[A-Z]{2}$/i', $postcode)) {
            return false;
        }   
        $prefix = substr($postcode, 0, 4);
        return self::where('postcode_pattern', $prefix)->exists();
    }
}

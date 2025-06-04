<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationInvitation extends Model
{
    use HasFactory;

    protected $table = 'registration_invitations'; // 表名

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    public $timestamps = false;
}

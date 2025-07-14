<?php

namespace App\Features\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationInvitation extends Model
{
    use HasFactory;

    protected $table = 'registration_invitations';

    protected $fillable = [
        'email',
        'token',
        'role',
        'created_at',
    ];

    public $timestamps = false;
}

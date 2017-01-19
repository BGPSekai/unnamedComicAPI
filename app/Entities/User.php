<?php

namespace App\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'from', 'sex', 'birthday', 'location', 'sign', 'blocked_until',
    ];

    protected $hidden = [
        'password',
    ];
}

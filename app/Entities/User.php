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

    public function comics()
    {
    	return $this->hasMany('App\Entities\Comic', 'published_by');
    }

    public function chapters()
    {
    	return $this->hasMany('App\Entities\Chapter', 'published_by');
    }

    public function favorites()
    {
    	$this->hasMany('App\Entities\Favorite', 'uid');
    }

    public function tags()
    {
        $this->hasMany('App\Entities\Tag', 'tagged_by');
    }
}

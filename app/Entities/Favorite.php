<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'uid', 'comic_id',
    ];

    public function users()
    {
    	return $this->belongsTo('App\Entities\User', 'uid');
    }

    public function comics()
    {
    	return $this->belongsTo('App\Entities\Comic');
    }
}

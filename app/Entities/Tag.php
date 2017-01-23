<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'comic_id', 'name', 'tagged_by',
    ];

    public function comics()
    {
    	return $this->belongsToMany('App\Entities\Comic');
    }

    // public function user()
    // {
    // 	return $this->belongsTo('App\Entities\User', 'tagged_by');
    // }
}

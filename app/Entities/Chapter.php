<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'comic_id', 'name', 'pages', 'published_by',
    ];

    public function user()
    {
    	return $this->belongsTo('App\Entities\User', 'published_by')->select('id', 'name');
    }

    public function comments()
    {
        return $this->hasMany('App\Entities\Comment');
    }
}

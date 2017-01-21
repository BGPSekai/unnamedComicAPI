<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    protected $fillable = [
        'name', 'summary', 'author', 'type', 'published_by', 'chapter_count', 'favorite_count',
    ];

    public function user()
    {
    	return $this->belongsTo('App\Entities\User', 'published_by')->select('id', 'name');
    }

    public function chapters()
    {
    	return $this->hasMany('App\Entities\Chapter');
    }

    public function favorites()
    {
        return $this->hasMany('App\Entities\Favorite');
    }

    public function tags()
    {
    	return $this->hasMany('App\Entities\Tag')->select('name');
    }

    public function comments()
    {
        return $this->hasMany('App\Entities\Comment');
    }
}

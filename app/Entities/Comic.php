<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    protected $fillable = [
        'name', 'summary', 'author', 'types', 'published_by', 'chapter_count', 'favorite_count',
    ];

    public function user()
    {
    	return $this->belongsTo('App\Entities\User', 'published_by')->select('id', 'name');
    }

    public function chapters()
    {
    	return $this->hasMany('App\Entities\Chapter');
    }

    public function favorited_users() //名稱待修正
    {
        return $this->belongsToMany('App\Entities\User', 'favorites', 'comic_id', 'uid');
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

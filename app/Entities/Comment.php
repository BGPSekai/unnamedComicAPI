<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'id', 'comic_id', 'chapter_id', 'comment', 'commented_by', 'replies',
    ];

    public function comic()
    {
    	return $this->hasOne('App\Entities\Comic');
    }

    public function chapter()
    {
    	return $this->hasOne('App\Entities\Chapter');
    }

    public function user()
    {
    	return $this->hasOne('App\Entities\User', 'commented_by');
    }
}

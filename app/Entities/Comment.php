<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'id', 'comic_id', 'chapter_id', 'comment', 'commented_by', 'reply_count',
    ];

    public function user()
    {
    	return $this->hasOne('App\Entities\User', 'commented_by')->select('id', 'name', 'avatar');
    }
}

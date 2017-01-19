<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'id', 'comic_id', 'chapter_id', 'comment', 'commented_by', 'replies',
    ];
}

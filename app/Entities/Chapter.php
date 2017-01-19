<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'comic_id', 'name', 'pages', 'published_by',
    ];
}

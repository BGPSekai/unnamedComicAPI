<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = [
        'comic_id', 'name', 'tag_by',
    ];
}

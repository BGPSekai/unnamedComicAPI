<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Comic extends Model
{
    protected $fillable = [
        'name', 'summary', 'author', 'type', 'published_by', 'chapters', 'favorites',
    ];
}

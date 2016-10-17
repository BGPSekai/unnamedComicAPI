<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = [
        'uid', 'comic_id',
    ];
}

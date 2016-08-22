<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $fillable = [
        'name', 'is_tag', 'create_by',
    ];
}

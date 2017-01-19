<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'comic_id', 'name', 'pages', 'published_by',
    ];

    public function comic()
    {
    	return $this->belongsTo('App\Entities\Comic');
    }

    public function user()
    {
    	return $this->belongsTo('App\Entities\User', 'published_by');
    }
}

<?php

namespace App\Repositories;

use App\Entities\Comment;

class CommentRepository
{
	public function create(array $data)
	{
		return 
	        Comment::create([
	        	'comic_id' => $data['comic_id'],
	            'chapter_id' => $data['chapter_id'],
	            'comment' => $data['comment'],
	            'comment_by' => $data['comment_by'],
	        ]);
	}
}

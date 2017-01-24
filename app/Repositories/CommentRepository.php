<?php

namespace App\Repositories;

use App\Entities\Comment;
use App\Entities\User;

class CommentRepository
{
	public function __construct()
	{
		$this->limit_per_page = 20;
	}

	public function index($type, $id, $page)
	{
		$comments_ids = array_unique(Comment::orderBy('id', 'desc')->where($type.'_id', $id)->pluck('id')->toArray());
		$result['pages'] = ceil(count($comments_ids) / $this->limit_per_page);
		$comments_ids = array_slice($comments_ids, ($page - 1) * $this->limit_per_page, $this->limit_per_page);
		$comments = [];
		foreach ($comments_ids as $comment_id)
			array_push($comments, Comment::orderBy('created_at', 'desc')->find($comment_id));
		$result['comments'] = $this->detail($comments);
		return $result;
	}

	public function create(array $data)
	{
		return 
	        Comment::create([
	        	'comic_id' => $data['comic_id'],
	            'chapter_id' => $data['chapter_id'],
	            'comment' => $data['comment'],
	            'commented_by' => $data['commented_by'],
	        ]);
	}

	public function find($id)
	{
		return Comment::find($id);
	}

	public function update(array $data)
	{
		return 
	        Comment::create([
	        	'id' => $data['id'],
	        	'comic_id' => $data['comic_id'],
	            'chapter_id' => $data['chapter_id'],
	            'comment' => $data['comment'],
	            'commented_by' => $data['commented_by'],
	        ]);
	}

	private function detail($comments)
    {
        foreach ($comments as $comment)
            $comment->commented_by = User::select('id', 'name', 'avatar')->find($comment->commented_by);

        return $comments;
    }
}

<?php

namespace App\Repositories;

use App\Entities\Comment;
use App\Entities\User;

class CommentRepository
{
	public function index($type, $id, $page)
	{
		$comments = Comment::where($type.'_id', $id)->orderBy('id', 'desc')->skip(($page - 1) * 10)->take(10)->get();
		$result['comments'] = $this->detail($comments);
		$result['pages'] = ceil(Comment::where($type.'_id', $id)->count() / 10);
		return $result;
	}

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
	            'comment_by' => $data['comment_by'],
	        ]);
	}

	private function detail($comments)
    {
        foreach ($comments as $comment)
            $comment->comment_by = User::select('id', 'name', 'avatar')->find($comment->comment_by);

        return $comments;
    }
}

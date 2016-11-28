<?php

namespace App\Repositories;

use App\Entities\Comment;

class CommentRepository
{
	public function comic($id, $page)
	{
        $comments = Comment::where('comic_id', $id)->orderBy('id', 'desc')->skip(($page - 1) * 10)->take(10)->get();
        $result['comments'] = $comments;
        $result['pages'] = ceil(Comment::where('comic_id', $id)->count()/10);
        return $result;
	}

	public function chapter($id, $page)
	{
        $comments = Comment::where('chapter_id', $id)->orderBy('id', 'desc')->skip(($page - 1) * 10)->take(10)->get();
        $result['comments'] = $comments;
        $result['pages'] = ceil(Comment::where('chapter_id', $id)->count()/10);
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
}

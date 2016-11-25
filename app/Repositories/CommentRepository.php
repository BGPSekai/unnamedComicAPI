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

	public function find($comic_id)
	{
		$chapters = Chapter::where('comic_id', $comic_id)->get();
        foreach ($chapters as $chapter)
            $chapter->publish_by = User::select('id', 'name')->find($chapter['publish_by']);
		return $chapters;
	}

	public function show($id)
	{
		return Chapter::find($id);
	}

	public function count($comic_id)
	{
		return Chapter::where('comic_id', $comic_id)->count();
	}

	public function updatePages($id, $pages)
	{
		return
			Chapter::where('id', $id)->update(['pages' => $pages]);
	}
}

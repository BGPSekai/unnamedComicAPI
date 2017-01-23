<?php

namespace App\Repositories;

use App\Entities\Chapter;

class ChapterRepository
{
	public function create(array $data)
	{
		return 
	        Chapter::create([
	        	'comic_id' => $data['comic_id'],
	            'name' => $data['name'],
	            'pages' => $data['pages'],
	            'published_by' => $data['published_by'],
	        ]);
	}

	public function find($comic_id)
	{
		$chapters = Chapter::where('comic_id', $comic_id)->get();
        foreach ($chapters as $chapter) {
            $chapter->published_by = $chapter->user;
            unset($chapter->user);
        }
		return $chapters;
	}

	public function show($id)
	{
		return Chapter::find($id);
	}

	public function updatePages($id, $pages)
	{
		return
			Chapter::where('id', $id)->update(['pages' => $pages]);
	}
}

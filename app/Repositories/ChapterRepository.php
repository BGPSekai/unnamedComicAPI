<?php

namespace App\Repositories;

use App\Entities\Chapter;
use App\Entities\User;

class ChapterRepository
{
	public function create(array $data)
	{
		return 
	        Chapter::create([
	        	'comic_id' => $data['comic_id'],
	            'name' => $data['name'],
	            'pages' => $data['pages'],
	            'publish_by' => $data['publish_by'],
	        ]);
	}

	public function find($comic_id)
	{
		$chapters = Chapter::where('comic_id', $comic_id)->get();
        foreach ($chapters as $chapter)
            $chapter['publish_by'] = User::select('id', 'name')->find($chapter['publish_by']);

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

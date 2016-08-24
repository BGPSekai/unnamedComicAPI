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
	            'publish_by' => $data['publish_by'],
	        ]);;
	}

	public function find($id)
	{
		return Chapter::where('comic_id', $id)->get();
	}

	public function show($id)
	{
		return Chapter::find($id);
	}

	public function count($id)
	{
		return Chapter::where('comic_id', $id)->count();
	}

	public function updatePages($id, $pages)
	{
		return
			Chapter::where('id', $id)->update(['pages' => $pages]);
	}
}

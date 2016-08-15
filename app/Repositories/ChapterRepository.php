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
	        ]);;
	}

	public function show($id)
	{
		return
			Chapter::where('comic_id', $id)->get();
	}

	public function count($id)
	{
		return Chapter::where('comic_id', $id)->count();
	}
}

<?php

namespace App\Repositories;

use App\Entities\Comic;

class ComicRepository
{
	public function create(array $data)
	{
		return 
	        Comic::create([
	            'name' => $data['name'],
	            'summary' => $data['summary'],
	            'publish_by' => $data['publish_by'],
	        ]);;
	}

	public function index($page)
	{
		return
			Comic::orderBy('updated_at', 'desc')->skip(($page - 1) * 10)->take(10)->get();
	}

	public function show($id)
	{
		return
			Comic::get()->find($id);
	}

	public function updateChapters($id, $chapters)
	{
		return Comic::find($id)->update(['chapters' => $chapters]);
	}
}

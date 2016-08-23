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
	            'author' => $data['author'],
	            'type' => $data['type'],
	            'publish_by' => $data['publish_by'],
	        ]);;
	}

	public function index($page)
	{
		return
			Comic::orderBy('id', 'desc')->skip(($page - 1) * 10)->take(10)->get();
	}

	public function show($id)
	{
		return Comic::find($id);
	}

	public function updateChapters($id, $chapters)
	{
		return Comic::find($id)->update(['chapters' => $chapters]);
	}

	public function searchName($name, $page)
	{
		return Comic::where('name', 'LIKE', '%'.$name.'%')->skip(($page - 1) * 10)->take(10)->get();
	}

	public function searchPublisher($user_id, $page)
	{
		return Comic::where('publish_by', $user_id)->skip(($page - 1) * 10)->take(10)->get();
	}

	public function count()
	{
		return ceil(Comic::count()/10);
	}

	public function countNameSearch($name)
	{
		return ceil(Comic::where('name', 'LIKE', '%'.$name.'%')->count()/10);
	}

	public function countPublisherSearch($user_id)
	{
		return ceil(Comic::where('publish_by', $user_id)->count()/10);
	}
}

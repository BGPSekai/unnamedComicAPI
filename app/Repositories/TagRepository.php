<?php

namespace App\Repositories;

use App\Entities\Tag;

class TagRepository
{
	public function search($name)
	{
		return
			Tag::where('name', 'LIKE', '%'.$name.'%')->pluck('name');
	}

	public function store($data)
	{
		$tag = Tag::where('comic_id', $data['comic_id'])
			->where('name', $data['name'])
			->first();

		if ($tag)
			return false;

		return Tag::create($data);
	}

	public function destroy($name, $comic_id)
	{
		$tag = Tag::where('name', $name)
			->where('comic_id', $comic_id)
			->first()
			->id;

		return Tag::destroy($tag);
	}

	public function show($comic_id)
	{
		return Tag::where('comic_id', $comic_id)->pluck('name');
	}
}

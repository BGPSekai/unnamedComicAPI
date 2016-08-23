<?php

namespace App\Repositories;

use App\Entities\Tag;
use App\Repositories\ComicRepository;

class TagRepository
{
    public function __construct(ComicRepository $comicRepo)
    {
        $this->comicRepo = $comicRepo;
    }

	public function find($name, $page)
	{
		$tags = Tag::where('tag', $name)->skip(($page - 1) * 10)->take(10)->get();
		$comics = [];
		foreach ($tags as $key => $tag) {
			array_push($comics, $this->comicRepo->show($tag->comic_id));
		}
		return $comics;
	}

	public function store($data)
	{
		$tag = Tag::where('comic_id', $data['comic_id'])
			->where('tag', $data['tag'])
			->first();

		if ($tag)
			return false;

		return Tag::create($data);
	}

	public function destroy($name, $comic_id)
	{
		$tag = Tag::where('tag', $name)
			->where('comic_id', $comic_id)
			->first()
			->id;
		return Tag::destroy($tag);
	}

	public function count($name)
	{
		return ceil(Tag::where('tag', $name)->count()/10);
	}

	public function show($comic_id)
	{
		return Tag::where('comic_id', $comic_id)->get();
	}
}

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
}

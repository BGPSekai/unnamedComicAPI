<?php

namespace App\Repositories;

use App\Entities\Comic;
use App\Entities\User;
use App\Entities\Tag;

class ComicRepository
{
    public function __construct()
    {
		$this->limit_per_page = 20;
	}

	public function create(array $data)
	{
		$comic = Comic::create([
            'name' => $data['name'],
            'summary' => $data['summary'],
            'author' => $data['author'],
            'types' => json_encode($data['types']),
            'published_by' => $data['published_by'],
        ]);

		return $comic;
	}

	public function index($page)
	{
		$comics = Comic::orderBy('id', 'desc')
			->skip(($page - 1) * $this->limit_per_page)
			->take($this->limit_per_page)
			->get();

        foreach ($comics as $comic)
        	$this->detail($comic);

		$result['comics'] = $comics;
		$result['pages'] = ceil(Comic::count()/$this->limit_per_page);
		return $result;
	}

	public function show($id)
	{
		if (! $comic = Comic::find($id))
			return false;

		$this->detail($comic);
        $comic->chapters;
		return $comic;
	}

	public function updateChapters($id)
	{
		return Comic::find($id)->increment('chapter_count');
	}

	public function sortByUpdateTime($comics)
	{
		return
			Comic::find($comics)->sortByDesc('updated_at')->pluck('id');
	}

	private function detail($comic)
	{
        $comic->types = json_decode($comic->types);
        $comic->tags;
        $comic->published_by = $comic->user;
        unset($comic->user);

		return $comic;
	}
}

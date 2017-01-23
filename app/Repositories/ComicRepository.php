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
		$comics = Comic::orderBy('id', 'desc')->skip(($page - 1) * $this->limit_per_page)->take($this->limit_per_page)->get();
        foreach ($comics as $comic) {
            $comic->types = json_decode($comic->types);
            $comic->tags = Tag::where('comic_id', $comic->id)->pluck('name');
            $comic->published_by = User::select('id', 'name')->find($comic->published_by);
        }
		$result['comics'] = $comics;
		$result['pages'] = ceil(Comic::count()/$this->limit_per_page);
		return $result;
	}

	public function show($id)
	{
		$comic = Comic::find($id);
		if (!$comic)
			return false;
        $comic->types = json_decode($comic->types);
        $comic->tags;
        $comic->published_by = $comic->user;
        unset($comic->user);
        $comic->chapters;
		return $comic;
	}

	// public function updateChapters($id, $chapters)
	public function updateChapters($id)
	{
	// 	return Comic::find($id)->update(['chapters' => $chapters]);
		return Comic::find($id)->increment('chapter_count');
	}

	public function sortByUpdateTime($comics)
	{
		return
			Comic::find($comics)->sortByDesc('updated_at')->pluck('id');
	}
}

<?php

namespace App\Repositories;

use App\Entities\Comic;
use App\Entities\User;
use App\Entities\Type;
use App\Entities\Tag;

class ComicRepository
{
	public function create(array $data)
	{
		$comic = Comic::create([
            'name' => $data['name'],
            'summary' => $data['summary'],
            'author' => $data['author'],
            'type' => json_encode($data['type']),
            'published_by' => $data['published_by'],
        ]);

		return $comic;
	}

	public function index($page)
	{
		$comics = Comic::orderBy('id', 'desc')->skip(($page - 1) * 20)->take(20)->get();
        foreach ($comics as $comic) {
            $comic->type = Type::select('id', 'name')->find($comic->type);
            $comic->tags = Tag::where('comic_id', $comic->id)->pluck('name');
            $comic->published_by = User::select('id', 'name')->find($comic->published_by);
        }
		$result['comics'] = $comics;
		$result['pages'] = ceil(Comic::count()/20);
		return $result;
	}

	public function show($id)
	{
		$comic = Comic::find($id);
        $comic->type = json_decode($comic->type);
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

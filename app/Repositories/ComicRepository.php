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
            'type' => $data['type'],
            'publish_by' => $data['publish_by'],
        ]);
        $comic->type = Type::select('id', 'name')->find($comic->type);
		return $comic;
	        
	}

	public function index($page)
	{
		$comics = Comic::orderBy('id', 'desc')->skip(($page - 1) * 10)->take(10)->get();
        foreach ($comics as $comic) {
            $comic->type = Type::select('id', 'name')->find($comic->type);
            $comic->tags = Tag::where('comic_id', $comic->id)->pluck('name');
            $comic->publish_by = User::select('id', 'name')->find($comic->publish_by);
        }

		return $comics;
	}

	public function show($id)
	{
		$comic = Comic::find($id);
        $comic->type = Type::select('id', 'name')->find($comic->type);
        $comic->tags = Tag::where('comic_id', $comic->id)->pluck('name');
        $comic->publish_by = User::select('id', 'name')->find($comic->publish_by);
		return $comic;
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

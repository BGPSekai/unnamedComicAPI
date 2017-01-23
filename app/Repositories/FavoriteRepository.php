<?php

namespace App\Repositories;

use App\Entities\Comic;
use App\Entities\Favorite;
use App\Entities\User;

class FavoriteRepository
{
	public function store(array $data)
	{
		$favorite = Favorite::where('uid', $data['uid'])
			->where('comic_id', $data['comic_id'])
			->first();

		if ($favorite)
			return false;

		Comic::find($data['comic_id'])->increment('favorite_count');

		return Favorite::create($data);
	}

	public function destroy($uid, $comic_id)
	{
		$favorite = Favorite::where('uid', $uid)
			->where('comic_id', $comic_id)
			->first()
			->id;

		if ($favorite)
			Comic::find($comic_id)->decrement('favorite_count');

		return Favorite::destroy($favorite);
	}

	public function show($uid)
	{
		if (!User::find($uid))
			return false;

		$favorites = User::find($uid)->favorites->sortByDesc('updated_at');

		foreach ($favorites as $comic) {
			$comic->types = json_decode($comic->types);
			$comic->published_by = $comic->user;
			$comic->tags;
			unset($comic->user);
			unset($comic->pivot);
		}

		return $favorites;
	}
}

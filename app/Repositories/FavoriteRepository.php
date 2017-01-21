<?php

namespace App\Repositories;

use App\Entities\Comic;
use App\Entities\Favorite;

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

	// public function showUsers($comic_id)
	// {
		// return Favorite::where('comic_id', $comic_id)->pluck('uid');
	// }

	public function showComics($uid)
	{
		return Favorite::where('uid', $uid)->pluck('comic_id');
	}
}

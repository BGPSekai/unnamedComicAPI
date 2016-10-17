<?php

namespace App\Repositories;

use App\Entities\Favorite;

class FavoriteRepository
{
	public function store(array $data)
	{
		return 
	        Chapter::create([
	        	'uid' => $data['uid'];
	        	'comic_id' => $data['comic_id'],
	        ]);
	}

	public function destroy($uid, $comic_id)
	{
		$favorite = Favorite::where('uid', $uid)
			->where('comic_id', $comic_id)
			->first()
			->id;
		return Favorite::destroy($favorite);
	}

	// public function showUsers($comic_id)
	// {
		// return Tag::where('comic_id', $comic_id)->pluck('uid');
	// }

	public function showComics($uid)
	{
		return Favorite::where('uid', $uid)->pluck('comic_id');
	}
}

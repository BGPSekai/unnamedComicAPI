<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\ComicRepository;
use App\Repositories\FavoriteRepository;
use Auth;

class FavoriteController extends Controller
{
    public function __construct(ComicRepository $comicRepo, FavoriteRepository $favoriteRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->favoriteRepo = $favoriteRepo;
    }

    public function store($comic_id)
    {
        if (! $comic = $this->comicRepo->show($comic_id))
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found']);

        $uid = Auth::user()->id;

        $data = ['comic_id' => $comic_id, 'uid' => $uid];

        if (! $favorite = $this->favoriteRepo->store($data))
            return response()->json(['status' => 'error', 'message' => 'Favorite Exist']);

        $favorites = $this->favoriteRepo->show($uid);

        return response()->json(['status' => 'success', 'favorites' => $favorites]);
    }

    public function destroy($comic_id)
    {
        $uid = Auth::user()->id;
        
        try {
            $this->favoriteRepo->destroy($uid, $comic_id);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Favorite Not Found'], 404);
        }

        $favorites = $this->favoriteRepo->show($uid);

        return response()->json(['status' => 'success', 'favorites' => $favorites]);
    }

    public function show($uid)
    {
        if (! $favorites = $this->favoriteRepo->show($uid))
            return response()->json(['status' => 'error', 'message' => 'User Not Found'], 404);
        $favorites = $this->comicRepo->sortByUpdateTime($favorites->toArray());
        return response()->json(['status' => 'success', 'favorites' => $favorites]);
    }
}

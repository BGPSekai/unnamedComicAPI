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
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found'], 404);

        $uid = Auth::user()->id;

        $data = ['comic_id' => $comic_id, 'uid' => $uid];

        if (! $favorite = $this->favoriteRepo->store($data))
            return response()->json(['status' => 'error', 'message' => 'Favorite Exist'], 403);

        return response()->json(['status' => 'success', 'message' => 'Add Favorite Success']);
    }

    public function destroy($comic_id)
    {
        $uid = Auth::user()->id;
        
        try {
            $this->favoriteRepo->destroy($uid, $comic_id);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Favorite Not Found'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Delete Favorite Success']);
    }

    public function show($uid)
    {
        if (! $favorites = $this->favoriteRepo->show($uid))
            return response()->json(['status' => 'error', 'message' => 'User Not Found'], 404);

        return response()->json(['status' => 'success', 'favorites' => $favorites]);
    }
}

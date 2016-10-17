<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use App\Repositories\FavoriteRepository;
use Auth;

class TagController extends Controller
{
    public function __construct(ComicRepository $comicRepo, FavoriteRepository $favoriteRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->favoriteRepo = $favoriteRepo;
    }

    public function store($uid, $comic_id)
    {
        if (! $comic = $this->comicRepo->show($comic_id))
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found']);

        $data = ['comic_id' => $comic_id, 'uid' => Auth::user()->id];

        if (! $tag = $this->favoriteRepo->store($data))
            return response()->json(['status' => 'error', 'message' => 'Tag Exist']);

        $tags = $this->favoriteRepo->show($comic_id);

        return response()->json(['status' => 'success', 'tags' => $tags]);
    }

    public function destroy($name, $comic_id)
    {
        try {
            $this->favoriteRepo->destroy($name, $comic_id);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Tag Not Found'], 404);
        }

        $tags = $this->favoriteRepo->show($comic_id);

        return response()->json(['status' => 'success', 'tags' => $tags]);
    }
}

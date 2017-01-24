<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\FavoriteRepository;
use Auth;
use Validator;

class FavoriteController extends Controller
{
    public function __construct(FavoriteRepository $repo)
    {
        $this->repo = $repo;
    }

    public function store($comic_id)
    {
        $validator = $this->validator(['comic_id' => $comic_id]);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $uid = Auth::user()->id;

        $data = ['comic_id' => $comic_id, 'uid' => $uid];

        if (! $favorite = $this->repo->store($data))
            return response()->json(['status' => 'error', 'message' => 'Favorite Exist'], 403);

        return response()->json(['status' => 'success', 'message' => 'Add Favorite Success']);
    }

    public function destroy($comic_id)
    {
        $uid = Auth::user()->id;
        
        try {
            $this->repo->destroy($uid, $comic_id);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Favorite Not Found'], 404);
        }

        return response()->json(['status' => 'success', 'message' => 'Delete Favorite Success']);
    }

    public function show($uid)
    {
        if (! $favorites = $this->repo->show($uid))
            return response()->json(['status' => 'error', 'message' => 'User Not Found'], 404);

        return response()->json(['status' => 'success', 'favorites' => $favorites]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'comic_id' => 'required|exists:comics,id',
        ]);
    }
}

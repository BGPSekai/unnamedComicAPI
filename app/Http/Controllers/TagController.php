<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\TagRepository;
use Auth;
use Validator;

class TagController extends Controller
{
    public function __construct(TagRepository $repo)
    {
        $this->repo = $repo;
    }

    public function search($name)
    {
        return response()->json(['status' => 'success', 'tags' => $this->repo->search($name)]);
    }

    public function store($name, $comic_id)
    {
        $validator = $this->validator(['comic_id' => $comic_id]);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $data = ['comic_id' => $comic_id, 'name' => $name, 'tagged_by' => Auth::user()->id];

        if (! $tag = $this->repo->store($data))
            return response()->json(['status' => 'error', 'message' => 'Tag Exist'], 403);

        $tags = $this->repo->show($comic_id)->tags;

        return response()->json(['status' => 'success', 'tags' => $tags]);
    }

    public function destroy($name, $comic_id)
    {
        try {
            $this->repo->destroy($name, $comic_id);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Tag Not Found'], 404);
        }

        $tags = $this->repo->show($comic_id)->tags;

        return response()->json(['status' => 'success', 'tags' => $tags]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'comic_id' => 'required|exists:comic,id',
        ]);
    }
}

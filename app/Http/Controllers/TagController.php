<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\TypeRepository;
use App\Repositories\TagRepository;
use Auth;

class TagController extends Controller
{
    public function __construct(TypeRepository $typeRepo, TagRepository $tagRepo)
    {
        $this->middleware('jwt.auth', ['except' => ['show']]);
        $this->typeRepo = $typeRepo;
        $this->tagRepo = $tagRepo;
    }

    public function store($name, $comic_id)
    {
    	$data = ['comic_id' => $comic_id, 'tag' => $name, 'tag_by' => Auth::user()->id];

    	$tag = $this->tagRepo->store($data);
        $tag['tag_by'] = Auth::user();

        if (!$tag)
            return response()->json(['status' => 'error', 'message' => 'Tag Exist']);

    	return response()->json(['status' => 'success', 'tag' => $tag]);
    }

    public function destroy($name, $comic_id)
    {
        try {
            $this->tagRepo->destroy($name, $comic_id);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Tag Not Found'], 404);
        }

    	return response()->json(['status' => 'success']);
    }

    public function find($name, $page)
    {
        $comics = $this->tagRepo->find($name, $page);
        foreach ($comics as $key => $comic) {
            $comics[$key]['type'] = $this->typeRepo->show($comic['type']);
        }
        return response()->json(['status' => 'success', 'comics' => $comics]);
    }

    public function count($name)
    {
        return response()->json(['status' => 'success', 'pages' => $this->repo->count($name)]);
    }
}

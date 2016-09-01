<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\TypeRepository;
use App\Repositories\TagRepository;
use App\Repositories\UserRepository;
use App\Repositories\ComicRepository;
use Auth;

class TagController extends Controller
{
    public function __construct(TypeRepository $typeRepo, TagRepository $tagRepo, UserRepository $userRepo, ComicRepository $comicRepo)
    {
        $this->middleware('jwt.auth', ['except' => ['find', 'count']]);
        $this->typeRepo = $typeRepo;
        $this->tagRepo = $tagRepo;
        $this->userRepo = $userRepo;
        $this->comicRepo = $comicRepo;
    }

    public function store($name, $comic_id)
    {
        if (! $comic = $this->comicRepo->show($comic_id))
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found']);

        $data = ['comic_id' => $comic_id, 'tag' => $name, 'tag_by' => Auth::user()->id];

        if (! $tag = $this->tagRepo->store($data))
            return response()->json(['status' => 'error', 'message' => 'Tag Exist']);

        $tags = $this->tagRepo->show($comic_id);

        return response()->json(['status' => 'success', 'tags' => $tags]);
    }

    public function destroy($name, $comic_id)
    {
        try {
            $this->tagRepo->destroy($name, $comic_id);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Tag Not Found'], 404);
        }

        $tags = $this->tagRepo->show($comic_id);

        return response()->json(['status' => 'success', 'tags' => $tags]);
    }

    public function find($name, $page)
    {
        $comics = $this->tagRepo->find($name, $page);
        foreach ($comics as $comic) {
            $comic['publish_by'] = $this->userRepo->show($comic['publish_by']);
            $comic['type'] = $this->typeRepo->show($comic['type']);
            $comic['tags'] = $this->tagRepo->show($comic['id']);
        }
        
        return response()->json(['status' => 'success', 'comics' => $comics]);
    }

    public function count($name)
    {
        return response()->json(['status' => 'success', 'pages' => $this->tagRepo->count($name)]);
    }
}

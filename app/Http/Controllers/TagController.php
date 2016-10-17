<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use App\Repositories\TagRepository;
use Auth;

class TagController extends Controller
{
    public function __construct(ComicRepository $comicRepo, TagRepository $tagRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->tagRepo = $tagRepo;
    }

    public function store($name, $comic_id)
    {
        if (! $comic = $this->comicRepo->show($comic_id))
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found']);

        $data = ['comic_id' => $comic_id, 'name' => $name, 'tag_by' => Auth::user()->id];

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
}

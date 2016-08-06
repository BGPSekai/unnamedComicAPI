<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use Storage;
use Response;

class ComicController extends Controller
{
    private $repo;

    public function __construct(ComicRepository $repo)
    {
        $this->repo = $repo;
    }

    public function show($id)
    {
        $comic = $this->repo->show($id);
        if (!isset($comic))
            return response()->json(['status' => 'error', 'msg' => 'Comic not found.']);
        return response()->json(['status' => 'success', 'comic' => $comic]);
    }

    public function showCover($id)
    {
        $comic = $this->repo->show($id);
        if (!isset($comic))
            return response()->json(['status' => 'error', 'msg' => 'Comic not found.']);
        $cover_path = Storage::files('comics/'.$comic->id);
        return Response::download(storage_path().'/app/'.$cover_path[0]);
    }
}

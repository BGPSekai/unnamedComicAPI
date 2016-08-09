<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use App\Repositories\ChapterRepository;
use Storage;
use Response;
use JWTAuth;
use JWTFactory;

class ComicController extends Controller
{
    private $comicRepo;
    private $chapterRepo;

    public function __construct(ComicRepository $comicRepo, ChapterRepository $chapterRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->chapterRepo = $chapterRepo;
    }

    public function show($id)
    {
        $comic = $this->comicRepo->show($id);
        if (!isset($comic))
            return response()->json(['status' => 'error', 'msg' => 'Comic not found']);

        $chapters = $this->chapterRepo->showAll($id);
        foreach ($chapters as $key => $chapter) {
            $chapters[$key]['token'] = (string) JWTAuth::encode(
                JWTFactory::make([
                    'comic_id' => $id,
                    'chapter_id' => $chapter->id,
                    'imgs' => $chapter->imgs
                ]));
        }

        return response()->json(['status' => 'success', 'comic' => $comic, 'chapters' => $chapters]);
    }

    public function showCover($id)
    {
        $comic = $this->comicRepo->show($id);
        if (!isset($comic))
            return response()->json(['status' => 'error', 'msg' => 'Comic not found']);
        $cover_path = Storage::files('comics/'.$comic->id);
        return Response::download(storage_path().'/app/'.$cover_path[0]);
    }

    public function showAll($page)
    {
        $comics = $this->comicRepo->showAll($page);
        return response()->json(['status' => 'success', 'comics' => $comics]);
    }
}

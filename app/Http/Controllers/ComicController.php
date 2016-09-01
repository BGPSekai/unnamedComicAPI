<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use App\Repositories\ChapterRepository;
use App\Repositories\UserRepository;
use App\Repositories\TypeRepository;
use App\Repositories\TagRepository;
use Storage;
use Response;
use JWTAuth;
use JWTFactory;

class ComicController extends Controller
{
    private $comicRepo;
    private $chapterRepo;

    public function __construct(ComicRepository $comicRepo, ChapterRepository $chapterRepo, UserRepository $userRepo, TypeRepository $typeRepo, TagRepository $tagRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->chapterRepo = $chapterRepo;
        $this->userRepo = $userRepo;
        $this->typeRepo = $typeRepo;
        $this->tagRepo = $tagRepo;
    }

    public function index($page)
    {
        $comics = $this->comicRepo->index($page);
        foreach ($comics as $comic) {
            $comic->type = $this->typeRepo->show($comic->type);
            $comic->tags = $this->tagRepo->show($comic->id);
            $comic->publish_by = $this->userRepo->show($comic->publish_by);
        }
        
        return response()->json(['status' => 'success', 'comics' => $comics]);
    }

    public function show($id)
    {
        $comic = $this->comicRepo->show($id);
        if (!$comic)
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found'], 404);
        $comic['type'] = $this->typeRepo->show($comic['type']);
        $comic['tags'] = $this->tagRepo->show($comic['id']);
        $comic['publish_by'] = $this->userRepo->show($comic['publish_by']);

        $chapters = $this->chapterRepo->find($id);
        foreach ($chapters as $key => $chapter) {
            $chapters[$key]['publish_by'] = $this->userRepo->show($chapter['publish_by']);
            $chapters[$key]['token'] = (string) JWTAuth::encode(
                JWTFactory::make([
                    'comic_id' => $id,
                    'chapter_id' => $chapter->id,
                    'pages' => $chapter->pages
                ])
            );
        }

        return response()->json(['status' => 'success', 'comic' => $comic, 'chapters' => $chapters]);
    }

    public function showCover($id)
    {
        $comic = $this->comicRepo->show($id);
        if (!isset($comic))
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found'], 404);
        $file_path = Storage::files('comics/'.$comic->id);
        return Response::download(storage_path().'/app/'.$file_path[0]);
    }

    public function showPage($page)
    {
        try {
            if (!JWTAuth::getToken())
                return response()->json(['status' => 'error', 'message' => 'A Token Is Required'], 400);

            $info = JWTAuth::decode(JWTAuth::getToken())->toArray();
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 401);
        }

        $comic_id = $info['comic_id'];
        $chapter_id = $info['chapter_id'];
        $pages = $info['pages'];

        if ($page < 1 || $page > $pages)
            return response()->json(['status' => 'error', 'message' => 'Page Not Found'], 404);

        $file_path = storage::files('comics/'.$comic_id.'/'.$chapter_id);
        return Response::download(storage_path().'/app/'.$file_path[$page-1]);
    }

    public function count()
    {
        return response()->json(['status' => 'success', 'pages' => $this->comicRepo->count()]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\ComicRepository;
use App\Repositories\ChapterRepository;
use Response;
use Storage;
// use JWTAuth;
// use JWTFactory;
use Validator;

class ComicController extends Controller
{
    public function __construct(ComicRepository $comicRepo, ChapterRepository $chapterRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->chapterRepo = $chapterRepo;
    }

    public function index($page)
    {
        $result = $this->comicRepo->index($page);
        return response()->json(['status' => 'success', 'comics' => $result['comics'], 'pages' => $result['pages']]);
    }

    public function show($id)
    {
        if (! $comic = $this->comicRepo->show($id))
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found'], 404);

        // $chapters = $this->chapterRepo->find($id);
        // foreach ($chapters as $chapter) {
        //     $chapter['token'] = (string) JWTAuth::encode(
        //         JWTFactory::make([
        //             'comic_id' => $id,
        //             'chapter_id' => $chapter->id,
        //             'pages' => $chapter->pages
        //         ])
        //     );
        // }

        // return response()->json(['status' => 'success', 'comic' => $comic, 'chapters' => $chapters]);
        return response()->json(['status' => 'success', 'comic' => $comic]);
    }

    public function showCover($id)
    {
        if (! $comic = $this->comicRepo->show($id))
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found'], 404);

        $file_path = Storage::files('comics/'.$id);
        header("Access-Control-Allow-Origin: *");
        return Response::download(storage_path().'/app/'.$file_path[0]);
    }

    // public function showPage($page)
    public function showPage($chapter_id, $page)
    {
        // try {
        //     if (!JWTAuth::getToken())
        //         return response()->json(['status' => 'error', 'message' => 'A Token Is Required'], 400);

        //     $info = JWTAuth::decode(JWTAuth::getToken())->toArray();
        // } catch (\Exception $e) {
        //     return response()->json(['status' => 'error', 'message' => $e->getMessage()], 401);
        // }

        // try {
        //     $comic_id = $info['comic_id'];
        //     $chapter_id = $info['chapter_id'];
        //     $pages = $info['pages'];
        // } catch (\Exception $e) {
        //     return response()->json(['status' => 'error', 'message' => $e->getMessage()], 401);
        // }

        $chapter = $this->chapterRepo->show($chapter_id);
        $comic_id = $chapter->comic_id;
        $pages = $chapter->pages;

        if ($page < 1 || $page > $pages)
            return response()->json(['status' => 'error', 'message' => 'Page Not Found'], 404);

        $file_path = Storage::files('comics/'.$comic_id.'/'.$chapter_id);
        natsort($file_path);
        $file_path = array_values($file_path);
        header("Access-Control-Allow-Origin: *");
        return Response::download(storage_path().'/app/'.$file_path[--$page]);
    }
}

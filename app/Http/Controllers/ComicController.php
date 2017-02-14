<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\ComicRepository;
use App\Repositories\ChapterRepository;
use Response;
use Storage;
use Validator;
use File;

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

    public function showPage($chapter_id, $page)
    {
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

    public function update(Request $request, $id)
    {
        $data = $request->intersect('name', 'summary', 'author', 'types', 'cover');
        $data['id'] = $id;
        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        if ($request->cover) {
            $cover = $request->file('cover');
            $extension = explode('/', File::mimeType($cover))[1];
            Storage::put(
                'comics/'.$id.'/cover.'.$extension,
                file_get_contents($cover->getRealPath())
            );
        }

        unset($data['id']);
        $comic = $this->comicRepo->update($data, $id);
        return response()->json(['status' => 'success', 'comic' => $comic]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'id' => 'exists:comics',
            'name' => 'max:255',
            'summary' => 'max:255',
            'author' => 'max:255',
            'types' => 'Array',
            'types.*' => 'exists:types,id',
            'cover' => 'image',
        ]);
    }
}

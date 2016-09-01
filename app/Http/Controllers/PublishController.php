<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use App\Repositories\ChapterRepository;
use App\Repositories\TypeRepository;
use Auth;
use Storage;
use Validator;

class PublishController extends Controller
{
    private $comicRepo;
    private $chapterRepo;

    public function __construct(ComicRepository $comicRepo, ChapterRepository $chapterRepo, TypeRepository $typeRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->chapterRepo = $chapterRepo;
        $this->typeRepo = $typeRepo;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $data = $request->only('name', 'summary', 'author', 'type', 'cover');
        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
 
        $data['publish_by'] = $user->id;
        $comic = $this->comicRepo->create($data);
        $comic['type'] = $this->typeRepo->show($comic['type']);
        $comic['publish_by'] = ['id' => $user->id, 'name' => $user->name];

        $cover = $request->file('cover');
        $extension = $cover->getClientOriginalExtension();
        $this->storeFile('comics/'.$comic->id.'/cover.'.$extension, $cover);

        return response()->json(['status' => 'success','comic' => $comic]);
    }

    public function chapter(Request $request, $id)
    {
        if (!$this->comicRepo->show($id))
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found'], 404);

        $user = Auth::user();
        $data = $request->only('name', 'images');
        $validator = $this->chapterValidator($data);
        
        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $data['comic_id'] = $id;
        $data['pages'] = count($request->images);
        $data['publish_by'] = Auth::user()->id;
        $chapter = $this->chapterRepo->create($data);
        $chapter['publish_by'] = ['id' => $user->id, 'name' => $user->name];

        if (!$data['pages'])
            return response()->json(['status' => 'success', 'chapter' => $chapter]);

        foreach ($request->images as $key => $image) {
            $extension = $image->getClientOriginalExtension();
            $this->storeFile('comics/'.$id.'/'.$chapter->id.'/'.($key+1).'.'.$extension, $image);
        }

        $chapters = $this->chapterRepo->count($id);
        $this->comicRepo->updateChapters($id, $chapters);

        return response()->json(['status' => 'success', 'chapter' => $chapter]);
    }

    public function batch(Request $request, $chapter_id)
    {
        if (! $chapter = $this->chapterRepo->show($chapter_id))
            return response()->json(['status' => 'error', 'message' => 'Chapter Not Found'], 404);

        $data = $request->only('images');
        $validator = $this->batchValidator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $data['pages'] = count($request->images);
        $this->chapterRepo->updatePages($chapter_id, $chapter->pages + $data['pages']);

        foreach ($request->images as $key => $image) {
            $extension = $image->getClientOriginalExtension();
            $this->storeFile('comics/'.$chapter->comic_id.'/'.$chapter_id.'/'.($chapter->pages+$key+1).'.'.$extension, $image);
        }

        $chapter = $this->chapterRepo->show($chapter_id);
        $chapter['publish_by'] = ['id' => Auth::user()->id, 'name' => Auth::user()->name];
        return response()->json(['status' => 'success', 'chapter' => $chapter]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'summary' => 'required|max:255',
            'author' => 'required|max:255',
            'type' => 'required|exists:types,id',
            'cover' => 'required|image',
        ]);
    }

    private function chapterValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'images' => 'Array',
            'images.*' => 'image',
        ]);
    }

    private function batchValidator(array $data)
    {
        return Validator::make($data, [
            'images' => 'required|Array',
            'images.*' => 'image',
        ]);
    }

    private function storeFile($path, $file)
    {
        return Storage::put(
            $path,
            file_get_contents($file->getRealPath())
        );
    }
}

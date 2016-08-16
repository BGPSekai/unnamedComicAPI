<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use App\Repositories\ChapterRepository;
use Auth;
use Validator;
use Storage;

class PublishController extends Controller
{
    private $comicRepo;
    private $chapterRepo;

    public function __construct(ComicRepository $comicRepo, ChapterRepository $chapterRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->chapterRepo = $chapterRepo;
    }

    public function index(Request $request)
    {
		$data = $request->only('name', 'summary', 'cover');
        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
 
        $data['publish_by'] = Auth::user()->id;
        $comic = $this->comicRepo->create($data);

        $cover = $request->file('cover');
        $extension = $cover->getClientOriginalExtension();
        $this->storeFile('comics/'.$comic->id.'/cover.'.$extension, $cover);

        return response()->json(['status' => 'success','comic' => $comic]);
    }

    public function chapter(Request $request, $id)
    {
        if ($this->comicRepo->show($id) == null)
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found'], 404);
        $data = $request->only('name', 'images');
        $data['comic_id'] = $id;
        $data['pages'] = count($request->images);
        $data['publish_by'] = Auth::user()->id;
        $validator = $this->chapterValidator($data);
        
        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $chapter = $this->chapterRepo->create($data);

        foreach ($request->images as $key => $image) {
            $extension = $image->getClientOriginalExtension();
            $this->storeFile('comics/'.$id.'/'.$chapter->id.'/'.($key+1).'.'.$extension, $image);
        }

        $chapters = $this->chapterRepo->count($id);
        $this->comicRepo->updateChapters($id, $chapters);

        return response()->json(['status' => 'success', 'chapter' => $chapter]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'summary' => 'required|max:255',
            'cover' => 'required|image',
        ]);
    }

    private function chapterValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Validator;
use App\Repositories\ComicRepository;
use App\Repositories\ChapterRepository;
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
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->all()]);
 
        $comic = $this->comicRepo->create($data);

        $cover = $request->file('cover');
        $extension = $cover->getClientOriginalExtension();
        $this->storeFile('comics/'.$comic->id.'/cover.'.$extension, $cover);

        return response()->json(['status' => 'success','info' => $comic]);
    }

    public function chapter(Request $request, $id)
    {
        if ($this->comicRepo->show($id) == null)
            return response()->json(['status' => 'error', 'msg' => 'Comic does not exist.'], 404);
        $data = $request->only('name', 'image');
        $data['comic_id'] = $id;
        $validator = $this->chapterValidator($data);
        
        if ($validator->fails())
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->all()]);

        $chapter = $this->chapterRepo->create($data);

        foreach ($request->image as $key => $img) {
            $extension = $img->getClientOriginalExtension();
            $this->storeFile('comics/'.$id.'/'.$chapter->id.'/'.$key.'.'.$extension, $img);
        }

        return response()->json(['status' => 'success', 'msg' => 'Upload successful.']);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'summary' => 'required|min:30',
            'cover' => 'required|image',
        ]);
    }

    private function chapterValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'image' => 'required|Array',
            'image.*' => 'image',
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

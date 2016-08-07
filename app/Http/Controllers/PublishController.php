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
        $data = $request->only('name', 'image');
        $data['comic_id'] = $id;
        $validator = $this->chapterValidator($data);
        
        if ($validator->fails())
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->all()]);
        else if (!$request->hasFile('image'))
            return response()->json(['status' => 'error', 'msg' => 'The image[] field is required.']);

        $chapter = $this->chapterRepo->create($data);

        foreach ($request->image as $img) {
            $fileName = $img->getClientOriginalName();
            $this->storeFile('comics/'.$id.'/'.$chapter->id.'/'.$fileName, $img);
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
// 已知BUG: 中文檔名、驗證image、檔案過多崩潰

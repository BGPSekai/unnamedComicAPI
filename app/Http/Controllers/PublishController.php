<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\ComicRepository;
use App\Repositories\ChapterRepository;
use Auth;
use File;
use Storage;
use Validator;

class PublishController extends Controller
{
    public function __construct(ComicRepository $comicRepo, ChapterRepository $chapterRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->chapterRepo = $chapterRepo;
    }

    public function comic(Request $request)
    {
        $user = Auth::user();
        $data = $request->only('name', 'summary', 'author', 'types', 'cover');
        $validator = $this->comicValidator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $data['published_by'] = $user->id;
        $comic = $this->comicRepo->create($data);
        $comic['published_by'] = ['id' => $user->id, 'name' => $user->name];
        $comic['types'] = json_decode($comic['types']);

        $cover = $request->file('cover');
        $extension = explode('/', File::mimeType($cover))[1];
        $this->storeFile('comics/'.$comic->id.'/cover.'.$extension, $cover);

        return response()->json(['status' => 'success', 'comic' => $comic]);
    }

    public function chapter(Request $request, $comic_id)
    {
        $user = Auth::user();
        $data = $request->only('name');
        $data['comic_id'] = $comic_id;
        $validator = $this->chapterValidator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $data['comic_id'] = $comic_id;
        $data['published_by'] = Auth::user()->id;
        $chapter = $this->chapterRepo->create($data);
        $chapter['published_by'] = ['id' => $user->id, 'name' => $user->name];

        $this->comicRepo->incrementChapterCount($comic_id);
        
        return response()->json(['status' => 'success', 'chapter' => $chapter]);
    }

    public function batch(Request $request, $chapter_id)
    {
        $user = Auth::user();
        $data = $request->only('index', 'images', 'new_index');
        $data['chapter_id'] = $chapter_id;
        $validator = $this->batchValidator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        if (isset($request->new_index)) {
            $response = $this->reSort($chapter_id, $request->new_index);
            switch ($response) {
                case -1:
                    return response()->json(['status' => 'error', 'message' => 'Length of new_index[] Not Match Pages'], 400);
                    break;
                case -2:
                    return response()->json(['status' => 'error', 'message' => 'new_index[] Duplicate'], 400);
                    break;
                default:
                    break;
            }
        }

        $comic_id = $this->chapterRepo->show($chapter_id)->comic_id;
        if (isset($request->images))
            foreach ($request->images as $key => $image) {
                $extension = explode('/', File::mimeType($image))[1];
                $this->storeFile('comics/'.$comic_id.'/'.$chapter_id.'/'.$request->index[$key].'.'.$extension, $image);
            }

        $data['pages'] = count(Storage::files('comics/'.$comic_id.'/'.$chapter_id));
        $this->chapterRepo->updatePages($chapter_id, $data['pages']);

        $chapter = $this->chapterRepo->show($chapter_id);
        $chapter['published_by'] = ['id' => $user->id, 'name' => $user->name];
        return response()->json(['status' => 'success', 'chapter' => $chapter]);
    }

    private function storeFile($path, $file)
    {
        return Storage::put(
            $path,
            file_get_contents($file->getRealPath())
        );
    }

    private function reSort($chapter_id, $index)
    {
        $index_zero = 0;
        $path = 'comics/'.$this->chapterRepo->show($chapter_id)->comic_id.'/'.$chapter_id.'/';
        $detail_path = storage_path().'/app/'.$path;

        // $index是否與頁數相同
        if (count($index) != count(Storage::files($path)))
            return -1;

        // $index是否有重複
        foreach ($index as $value)
            $index_zero = !$value ? $index_zero : $index_zero+1;

        $index_zero = $index_zero ? $index_zero-1 : $index_zero;
        if (count($index) != count(array_unique($index)) + $index_zero)
            return -2;
        //

        foreach ($index as $key => $value) {
            if ($value == $key+1)
                continue;

            $old = glob($detail_path.($key+1).'.*.tmp') ? glob($detail_path.($key+1).'.*.tmp') : glob($detail_path.($key+1).'.*');
            $new = glob($detail_path.$value.'.*.tmp') ? glob($detail_path.$value.'.*.tmp') : glob($detail_path.$value.'.*');

            if (!$value) {
                if (isset($old[1]))
                    unlink($old[1]);
                else
                    unlink($old[0]);
                continue;
            }

            if (isset($new[0]))
                rename($new[0], $new[0].'.tmp');
            $old = isset($old[1]) ? $old[1] : $old[0];
            rename($old, $detail_path.$value.'.'.explode('/', File::mimeType($old))[1]);
        }

        return 0;
    }

    private function comicValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'summary' => 'required|max:255',
            'author' => 'required|max:255',
            'types' => 'required|Array',
            'types.*' => 'required|exists:types,id',
            'cover' => 'required|image',
        ]);
    }

    private function chapterValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'comic_id' => 'required|exists:comics,id',
        ]);
    }

    private function batchValidator(array $data)
    {
        return Validator::make($data, [
            'chapter_id' => 'required|exists:chapters,id',
            'index' => 'required_with:images|Array|nullable',
            'index.*' => 'required_with:index|integer|min:1',
            'images' => 'required_with:index|Array|nullable',
            'images.*' => 'required_with:images|image',
            'new_index' => 'required_without_all:index,images|Array|nullable',
            'new_index.*' => 'required_with:new_index|integer|min:0',
        ]);
    }
}

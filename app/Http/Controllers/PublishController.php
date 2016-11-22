<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use App\Repositories\ChapterRepository;
use Auth;
use File;
use JWTAuth;
use JWTFactory;
use Storage;
use Validator;

class PublishController extends Controller
{
    public function __construct(ComicRepository $comicRepo, ChapterRepository $chapterRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->chapterRepo = $chapterRepo;
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
        $comic['publish_by'] = ['id' => $user->id, 'name' => $user->name];

        $cover = $request->file('cover');
        $extension = $cover->getClientOriginalExtension();
        $this->storeFile('comics/'.$comic->id.'/cover.'.$extension, $cover);

        return response()->json(['status' => 'success', 'comic' => $comic]);
    }

    public function chapter(Request $request, $comic_id)
    {
        if (!$this->comicRepo->show($comic_id))
            return response()->json(['status' => 'error', 'message' => 'Comic Not Found'], 404);

        $user = Auth::user();
        $data = $request->only('name', 'images');
        $validator = $this->chapterValidator($data);
        
        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $data['comic_id'] = $comic_id;
        $data['pages'] = count($request->images);
        $data['publish_by'] = Auth::user()->id;
        $chapter = $this->chapterRepo->create($data);
        $chapter['publish_by'] = ['id' => $user->id, 'name' => $user->name];
        $chapter['token'] = (string) JWTAuth::encode(
            JWTFactory::make([
                'comic_id' => $comic_id,
                'chapter_id' => $chapter->id,
                'pages' => $chapter->pages
            ])
        );
        $chapters = $this->chapterRepo->count($comic_id);
        $this->comicRepo->updateChapters($comic_id, $chapters);
        
        if (!$data['pages'])
            return response()->json(['status' => 'success', 'chapter' => $chapter]);

        foreach ($request->images as $key => $image) {
            $extension = $image->getClientOriginalExtension();
            $this->storeFile('comics/'.$comic_id.'/'.$chapter->id.'/'.($key+1).'.'.$extension, $image);
        }

        return response()->json(['status' => 'success', 'chapter' => $chapter]);
    }

    // public function batch(Request $request, $chapter_id)
    // {
    //     if (! $chapter = $this->chapterRepo->show($chapter_id))
    //         return response()->json(['status' => 'error', 'message' => 'Chapter Not Found'], 404);

    //     $user = Auth::user();
    //     $data = $request->only('images');
    //     $validator = $this->batchValidator($data);

    //     if ($validator->fails())
    //         return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

    //     $data['pages'] = count($request->images);
    //     $this->chapterRepo->updatePages($chapter_id, $chapter->pages + $data['pages']);

    //     foreach ($request->images as $key => $image) {
    //         $extension = $image->getClientOriginalExtension();
    //         $this->storeFile('comics/'.$chapter->comic_id.'/'.$chapter_id.'/'.($chapter->pages+$key+1).'.'.$extension, $image);
    //     }

    //     $chapter = $this->chapterRepo->show($chapter_id);
    //     $chapter['publish_by'] = ['id' => $user->id, 'name' => $user->name];
    //     return response()->json(['status' => 'success', 'chapter' => $chapter]);
    // }

    public function batch(Request $request, $chapter_id)
    {
        if (! $chapter = $this->chapterRepo->show($chapter_id))
            return response()->json(['status' => 'error', 'message' => 'Chapter Not Found'], 404);

        $user = Auth::user();
        $data = $request->only('index', 'images', 'new_index');
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

        if (isset($request->images))
            foreach ($request->images as $key => $image) {
                $extension = $image->getClientOriginalExtension();
                $this->storeFile('comics/'.$chapter->comic_id.'/'.$chapter_id.'/'.$request->index[$key].'.'.$extension, $image);
            }

        $data['pages'] = count(Storage::files('comics/'.$chapter->comic_id.'/'.$chapter_id));
        $this->chapterRepo->updatePages($chapter_id, $data['pages']);

        $chapter = $this->chapterRepo->show($chapter_id);
        $chapter['publish_by'] = ['id' => $user->id, 'name' => $user->name];
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
            'images' => 'Array|nullable',
            'images.*' => 'required_with:images|image',
        ]);
    }

    private function batchValidator(array $data)
    {
        // if (isset($data['index']) || isset($data['images']))
        //     return Validator::make($data, [
        //         'index' => 'required|Array',
        //         'index.*' => 'integer|min:1',
        //         'images' => 'required|Array',
        //         'images.*' => 'image',
        //         'new_index' => 'Array',
        //         'new_index.*' => 'integer|min:0',
        //     ]);
        // else
        //     return Validator::make($data, [
        //         'index' => 'Array',
        //         'index.*' => 'integer|min:1',
        //         'images' => 'Array',
        //         'images.*' => 'image',
        //         'new_index' => 'required|Array',
        //         'new_index.*' => 'integer|min:0',
        //     ]);
        return Validator::make($data, [
            'index' => 'required_with:images|Array|nullable',
            'index.*' => 'required_with:index|integer|min:1',
            'images' => 'required_with:index|Array|nullable',
            'images.*' => 'required_with:images|image',
            'new_index' => 'required_without_all:index,images|Array|nullable',
            'new_index.*' => 'required_with:new_index|integer|min:0',
        ]);
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
        foreach ($index as $key => $value) {
            if ($value == 0)
                $index_zero++;
        }

        if ($index_zero)
            $index_zero--;
        if (count($index) != count(array_unique($index)) + $index_zero)
            return -2;
        //

        foreach ($index as $key => $value) {
            if ($value == $key+1)
                continue;

            $old = glob($detail_path.($key+1).'.*.tmp') ? glob($detail_path.($key+1).'.*.tmp') : glob($detail_path.($key+1).'.*');
            $new = glob($detail_path.$value.'.*.tmp') ? glob($detail_path.$value.'.*.tmp') : glob($detail_path.$value.'.*');

            if ($value == 0) {
                if (isset($old[1]))
                    unlink($old[1]);
                else
                    unlink($old[0]);
                continue;
            }

            if (isset($new[0]))
                rename($new[0], $new[0].'.tmp');
            if (isset($old[1])) $old = $old[1];
            else $old = $old[0];
            rename($old, $detail_path.$value.'.'.explode('/', File::mimeType($old))[1]);
        }

        return 0;
    }
}

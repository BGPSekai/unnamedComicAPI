<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Validator;
use App\Repositories\ComicRepository;
use Storage;

class ComicPublishController extends Controller
{
    private $repo;

    public function __construct(ComicRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
		$data = $request->only('name', 'summary', 'cover');

        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->all()]);
 
        $comic = $this->repo->create($data);

        $extension = $request->cover->getClientOriginalExtension();

        Storage::put(
            'comics/'.$comic->id.'/cover.'.$extension,
            file_get_contents($request->file('cover')->getRealPath())
        );

        return response()->json(['info' => $comic]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'summary' => 'required|min:30',
            'cover' => 'required|image',
        ]);
    }
}

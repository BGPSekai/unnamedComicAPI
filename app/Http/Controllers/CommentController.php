<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\CommentRepository;
use Auth;
use Validator;

class CommentController extends Controller
{
    public function store(Request $request)
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
}

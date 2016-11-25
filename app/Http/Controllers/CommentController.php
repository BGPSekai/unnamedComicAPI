<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\CommentRepository;
use Auth;
use Validator;

class CommentController extends Controller
{
    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepo = $commentRepo;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $data = $request->only('comic_id', 'chapter_id', 'comment');
        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
 
        $data['comment_by'] = $user->id;
        $comment = $this->commentRepo->create($data);
        $comment['comment_by'] = ['id' => $user->id, 'name' => $user->name];

        return response()->json(['status' => 'success', 'comment' => $comment]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'comic_id' => 'required|exists:comics,id',
            'chapter_id' => 'exists:chapters,id|nullable',
            'comment' => 'required|max:255',
        ]);
    }
}

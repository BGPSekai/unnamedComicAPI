<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\CommentRepository;
use Auth;
use Validator;

class CommentController extends Controller
{
    public function __construct(CommentRepository $repo)
    {
        $this->repo = $repo;
    }

    public function comic($id, $page)
    {
        $result = $this->repo->comic($id, $page);
        return response()->json(['status' => 'success', 'comments' => $result['comments'], 'pages' => $result['pages']]);
    }

    public function chapter($id, $page)
    {
        $result = $this->repo->chapter($id, $page);
        return response()->json(['status' => 'success', 'comments' => $result['comments'], 'pages' => $result['pages']]);
    }

    public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();

        $data = $request->only('id', 'comic_id', 'chapter_id', 'comment');
        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $data['comment_by'] = $user->id;

        if ($data['id']) {
            $old_comment = $this->repo->find($data['id']);

            if ($user->id != $old_comment->comment_by)
                return response()->json(['status' => 'error', 'message' => 'Access is Denied'], 403);

            $data['comic_id'] = $old_comment->comic_id;
            $data['chapter_id'] = $old_comment->chapter_id;
        }

        $comment = $data['id'] ? $this->repo->update($data) : $this->repo->create($data);
        $comment['comment_by'] = ['id' => $user->id, 'name' => $user->name];

        return response()->json(['status' => 'success', 'comment' => $comment]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'id' => 'required_without:comic_id|exists:comments|nullable',
            'comic_id' => 'required_without:id|exists:comics,id|nullable',
            'chapter_id' => 'exists:chapters,id|nullable',
            'comment' => 'required|max:255',
        ]);
    }
}

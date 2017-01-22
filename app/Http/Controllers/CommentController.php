<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\ChapterRepository;
use App\Repositories\CommentRepository;
use Auth;
use Validator;

class CommentController extends Controller
{
    public function __construct(ChapterRepository $chapterRepo, CommentRepository $commentRepo)
    {
        $this->chapterRepo = $chapterRepo;
        $this->commentRepo = $commentRepo;
    }

    public function comic($id, $page)
    {
        $result = $this->commentRepo->index('comic', $id, $page);
        return response()->json(['status' => 'success', 'comments' => $result['comments'], 'pages' => $result['pages']]);
    }

    public function chapter($id, $page)
    {
        $result = $this->commentRepo->index('chapter', $id, $page);
        return response()->json(['status' => 'success', 'comments' => $result['comments'], 'pages' => $result['pages']]);
    }

    public function storeOrUpdate(Request $request)
    {
        $user = Auth::user();

        $data = array_diff($request->only('id', 'comic_id', 'chapter_id', 'comment'), [null]);
        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        if (count($data) == 1)
            return response()->json(['status' => 'error', 'message' => 'The id, comic_id, or chapter_id field is required.'], 400);
        elseif (count($data) > 2)
            return response()->json(['status' => 'error', 'message' => 'Too Many Params'], 400);

        $data['commented_by'] = $user->id;

        if ($data['id']) {
            $old_comment = $this->commentRepo->find($data['id']);

            if ($user->id != $old_comment->commented_by)
                return response()->json(['status' => 'error', 'message' => 'Access is Denied'], 403);

            $data['comic_id'] = $old_comment->comic_id;
            $data['chapter_id'] = $old_comment->chapter_id;
        }
        elseif ($data['chapter_id']) {
            $data['comic_id'] = $this->chapterRepo->show($data['chapter_id'])->comic_id;
        }

        $comment = $data['id'] ? $this->commentRepo->update($data) : $this->commentRepo->create($data);
        $comment['commented_by'] = ['id' => $user->id, 'name' => $user->name];

        return response()->json(['status' => 'success', 'comment' => $comment]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            // 'id' => 'required_without_all:comic_id,chapter_id|exists:comments|nullable',
            // 'comic_id' => 'required_without_all:id,chapter_id|exists:comics,id|nullable',
            // 'chapter_id' => 'required_without_all:id,comic_id|exists:chapters,id|nullable',
            'id' => 'exists:comments',
            'comic_id' => 'exists:comics,id',
            'chapter_id' => 'exists:chapters,id',
            'comment' => 'required|max:255',
        ]);
    }
}

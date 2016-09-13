<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\UserRepository;
use Auth;

class UserController extends Controller
{
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return response()->json(['status' => 'success', 'user' => Auth::user()]);
    }

    public function show($id)
    {
        if (! $user = $this->repo->showDetail($id))
            return response()->json(['status' => 'error', 'message' => 'User Not Found'], 404);

        return response()->json(['status' => 'success', 'user' => $user]);
    }

    public function avatar(Request $request)
    {
        $user = Auth::user();
        $data = $request->only('image');
        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
 
        //User::has_avatar

        $avatar = $request->file('image');
        $extension = $avatar->getClientOriginalExtension();
        $this->storeFile('users/'.$user->id.$extension, $avatar);

        return response()->json(['status' => 'success');
    }

    public function showAvatar($id)
    {
        if (! $user = $this->repo->showDetail($id))
            return response()->json(['status' => 'error', 'message' => 'User Not Found'], 404);

        // $file_path = Storage::files('comics/'.$comic->id);
        // return Response::download(storage_path().'/app/'.$file_path[0]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'iamge' => 'required|image',
        ]);
    }
}

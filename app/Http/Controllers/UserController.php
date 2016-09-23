<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\UserRepository;
use Auth;
use Image;
use Validator;

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
 
        $avatar = $request->file('image');
        $extension = $avatar->getClientOriginalExtension();

        $path = public_path().'/users/';
        $file_name = $user->id.'.'.$extension;
        $avatar->move($path, $file_name);

        Image::make($path.$file_name)
            ->resize(200, 200)
            ->save($path.$file_name);

        $this->repo->avatar($user->id, $extension);

        return response()->json(['status' => 'success', 'user' => $this->repo->show($user->id)]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'image' => 'required|image',
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\UserRepository;
use Auth;
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

        $avatar->move(public_path().'/users/', $user->id.'.'.$extension);

        $this->repo->avatar($user->id, $extension);

        return response()->json(['status' => 'success', 'user' => Auth::user()]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'image' => 'required|image',
        ]);
    }
}

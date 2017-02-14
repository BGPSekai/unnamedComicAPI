<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\UserRepository;
use Auth;
use File;
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
        if (! $user = $this->repo->show($id))
            return response()->json(['status' => 'error', 'message' => 'User Not Found'], 404);

        return response()->json(['status' => 'success', 'user' => $user]);
    }

    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $data = $request->intersect('name', 'avatar', 'sex', 'birthday', 'location', 'sign');
        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);
 
        if ($avatar = $request->file('avatar')) {
            $extension = explode('/', File::mimeType($avatar))[1];

            $path = public_path().'/users/';
            $file_name = $id.'.'.$extension;

            File::delete(glob($path.$id.'.*'));
            $avatar->move($path, $file_name);

            Image::make($path.$file_name)
                ->resize(200, 200)
                ->save($path.$file_name);
            $data['avatar'] = $extension;
        }

        $this->repo->update($data, $id);

        $user = $this->repo->show($id);
        return response()->json(['status' => 'success', 'user' => $user]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'max:255',
            'avatar' => 'image',
            'sex' => 'boolean' ,
            'birthday' => 'date',
            'location' => 'max:255',
            'sign' => 'max:255'
        ]);
    }
}

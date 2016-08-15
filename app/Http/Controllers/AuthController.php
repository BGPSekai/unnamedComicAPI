<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\UserRepository;
use JWTAuth;
use Auth;
use Validator;

class AuthController extends Controller
{
    private $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation');

        $validator = $this->validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $user = $this->repo->create($data);

        return response()->json(['status' => 'success', 'user' => $user]);
    }

    public function auth(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (! $token = JWTAuth::attempt($credentials))
            return response()->json(['status' => 'error', 'message' => 'Invalid Credentials'], 401);
 
        return response()->json(['status' => 'success', 'token' => $token, 'user' => Auth::user()]);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }
}

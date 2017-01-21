<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\UserRepository;
use Auth;
use JWTAuth;
use Validator;

class AuthController extends Controller
{
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
        // $this->middleware('jwt.auth', ['except' => ['register', 'auth']]);
        $this->middleware('jwt.auth', ['only' => 'reset']);
    }

    public function register(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validator = $this->registerValidator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $data['from'] = null;
        $user = $this->repo->create($data);

        return response()->json(['status' => 'success', 'user' => $user]);
    }

    public function auth(Request $request)
    {
        $data = $request->only('name', 'email', 'password', 'from');
        $validator = $this->authValidator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        $credentials['email'] = $data['from'] ? $data['email'].'@'.$data['from'] : $data['email'];
        $credentials['password'] = $request->password;

        if (! $token = JWTAuth::attempt($credentials))
            if (!$data['from']) {
                return response()->json(['status' => 'error', 'message' => 'Invalid Credentials'], 401);
            } else {
                try {
                    $data['email'] = $credentials['email'];
                    $this->repo->create($data);
                    $token = JWTAuth::attempt($credentials);
                } catch (\Exception $e) {
                    return response()->json(['status' => 'error', 'message' => 'Invalid Credentials'], 401);
                }
            }

        return response()->json(['status' => 'success', 'token' => $token]);
    }

    public function reset(Request $request)
    {

        $credentials = ['email' => Auth::user()->email, 'password' => $request->password];

        if (!Auth::attempt($credentials))
            return response()->json(['error' => 'Invalid Credentials'], 401);

        $data = $request->only('new_password', 'new_password_confirmation');

        $validator = $this->resetValidator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 400);

        Auth::user()->forceFill([
            'password' => bcrypt($data['new_password']),
        ])->save();

        return response()->json(['status' => 'success', 'message' => 'Password Reset']);
    }

    private function registerValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    private function authValidator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required_with:from|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
        ]);
    }

    private function resetValidator(array $data)
    {
        return Validator::make($data, [
            'new_password' => 'required|min:6|confirmed',
        ]);
    }
}

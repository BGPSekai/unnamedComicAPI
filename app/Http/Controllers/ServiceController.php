<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Validator;
use App\Repositories\UserRepository;

class ServiceController extends Controller
{
    private $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function register(Request $request)
    {
		$data = $request->only('name', 'email', 'password', 'password_confirmation');

        $validator = validator($data);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->all()]);

        $this->repo->create($data);

        return response()->json(['status' => 'success', 'msg' => 'Register successful.']);
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

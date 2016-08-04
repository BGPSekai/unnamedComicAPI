<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Validator;
use App\User;

class ServiceController extends Controller
{
    public function register(Request $request)
    {
		$data = $request->all();

        $validator =  Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails())
            return response()->json(['status' => 'error', 'msg' => $validator->errors()->all()]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return response()->json(['status' => 'success', 'token' => '老子懶 晚點']);
    }
}

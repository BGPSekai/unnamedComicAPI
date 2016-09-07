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
}

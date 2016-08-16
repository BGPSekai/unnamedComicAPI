<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\UserRepository;

class UserController extends Controller
{
    private $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function show($id)
    {
    	$user = $this->repo->show($id);

        if (!$user)
            return response()->json(['status' => 'error', 'message' => 'User Not Found'], 404);

    	return response()->json(['status' => 'success', 'user' => $user]);
    }
}

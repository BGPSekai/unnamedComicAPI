<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\Web\UserRepository;

class UserController extends Controller
{
	public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
    	$users = $this->repo->index();
    	return view('user', compact('users'));
    }
}

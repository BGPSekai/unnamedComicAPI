<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\TypeRepository;

class TypeController extends Controller
{
    public function __construct(TypeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
    	$types = $this->repo->index();
    	return response()->json(['status' => 'success', 'types' => $types]);
    }
}

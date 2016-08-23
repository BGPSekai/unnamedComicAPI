<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

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

    public function show($id, $page)
    {
    	$comics = $this->repo->find($id, $page);
    	return response()->json(['status' => 'success', 'comics' => $comics]);
    }
}

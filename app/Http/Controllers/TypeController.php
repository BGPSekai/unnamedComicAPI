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

    public function find($id, $page)
    {
    	$comics = $this->repo->find($id, $page);
        foreach ($comics as $key => $comic) {
            $comics[$key]['type'] = $this->repo->show($comic['type']);
        }
    	return response()->json(['status' => 'success', 'comics' => $comics]);
    }

    public function count($id)
    {
        return response()->json(['status' => 'success', 'pages' => $this->repo->count($id)]);
    }
}

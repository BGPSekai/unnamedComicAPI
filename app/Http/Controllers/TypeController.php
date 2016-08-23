<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\TypeRepository;
use App\Repositories\TagRepository;

class TypeController extends Controller
{
    public function __construct(TypeRepository $typeRepo, TagRepository $tagRepo)
    {
        $this->typeRepo = $typeRepo;
        $this->tagRepo = $tagRepo;
    }

    public function index()
    {
    	$types = $this->typeRepo->index();
    	return response()->json(['status' => 'success', 'types' => $types]);
    }

    public function find($id, $page)
    {
    	$comics = $this->typeRepo->find($id, $page);
        foreach ($comics as $key => $comic) {
            $comics[$key]['type'] = $this->typeRepo->show($comic['type']);
            $comics[$key]['tags'] = $this->tagRepo->show($comic['id']);
        }
    	return response()->json(['status' => 'success', 'comics' => $comics]);
    }

    public function count($id)
    {
        return response()->json(['status' => 'success', 'pages' => $this->typeRepo->count($id)]);
    }
}

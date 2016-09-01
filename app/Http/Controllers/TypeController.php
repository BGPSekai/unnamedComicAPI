<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\UserRepository;
use App\Repositories\TypeRepository;
use App\Repositories\TagRepository;

class TypeController extends Controller
{
    public function __construct(UserRepository $userRepo, TypeRepository $typeRepo, TagRepository $tagRepo)
    {
        $this->userRepo = $userRepo;
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
        foreach ($comics as $comic) {
            $comic['type'] = $this->typeRepo->show($comic['type']);
            $comic['tags'] = $this->tagRepo->show($comic['id']);
            $comic['publish_by'] = $this->userRepo->show($comic['publish_by']);
        }

    	return response()->json(['status' => 'success', 'comics' => $comics]);
    }

    public function count($id)
    {
        return response()->json(['status' => 'success', 'pages' => $this->typeRepo->count($id)]);
    }
}

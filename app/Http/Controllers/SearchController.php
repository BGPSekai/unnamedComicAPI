<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use App\Repositories\UserRepository;
use App\Repositories\TypeRepository;
use Auth;

class SearchController extends Controller
{
    public function __construct(ComicRepository $comicRepo, UserRepository $userRepo, TypeRepository $typeRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->userRepo = $userRepo;
        $this->typeRepo = $typeRepo;
    }

    public function name($name, $page)
    {
        $result = $this->comicRepo->searchName($name, $page);
        foreach ($result as $key => $comic) {
            $result[$key]['type'] = $this->typeRepo->show($comic['type']);
            $result[$key]['publish_by'] = $this->userRepo->show($comic['publish_by']);
        }
        return response()->json(['status' => 'success', 'comics' => $result]);
    }

    public function publisher($user_id, $page)
    {
        $result = $this->comicRepo->searchPublisher($user_id, $page);
        foreach ($result as $key => $comic) {
            $result[$key]['type'] = $this->typeRepo->show($comic['type']);
            $result[$key]['publish_by'] = $this->userRepo->show($comic['publish_by']);
        }
        return response()->json(['status' => 'success', 'comics' => $result]);
    }

    public function countName($name)
    {
        return response()->json(['status' => 'success', 'pages' => $this->comicRepo->countNameSearch($name)]);
    }

    public function countPublisher($user_id)
    {
        return response()->json(['status' => 'success', 'pages' => $this->comicRepo->countPublisherSearch($user_id)]);
    }
}

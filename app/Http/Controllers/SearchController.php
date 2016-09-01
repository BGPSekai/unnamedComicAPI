<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use App\Repositories\UserRepository;
use App\Repositories\TypeRepository;
use App\Repositories\TagRepository;

class SearchController extends Controller
{
    public function __construct(ComicRepository $comicRepo, UserRepository $userRepo, TypeRepository $typeRepo, TagRepository $tagRepo)
    {
        $this->comicRepo = $comicRepo;
        $this->userRepo = $userRepo;
        $this->typeRepo = $typeRepo;
        $this->tagRepo = $tagRepo;
    }

    public function name($name, $page)
    {
        $result = $this->comicRepo->searchName($name, $page);
        return response()->json(['status' => 'success', 'comics' => $result]);
    }

    public function publisher($user_id, $page)
    {
        $result = $this->comicRepo->searchPublisher($user_id, $page);
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

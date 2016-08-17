<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\ComicRepository;
use Auth;

class SearchController extends Controller
{
    public function __construct(ComicRepository $comicRepo)
    {
        $this->comicRepo = $comicRepo;
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
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\SearchRepository;

class SearchController extends Controller
{
    public function __construct(SearchRepository $repo)
    {
        $this->repo = $repo;
    }

    public function name($name, $page)
    {
        $result = $this->repo->name($name, $page);
        return response()->json(['status' => 'success', 'comics' => $result['comics'], 'pages' => $result['pages']]);
    }

    public function publisher($user_id, $page)
    {
        $result = $this->repo->publisher($user_id, $page);
        return response()->json(['status' => 'success', 'comics' => $result['comics'], 'pages' => $result['pages']]);
    }

    public function type($id, $page)
    {
        $result = $this->repo->type($id, $page);
        return response()->json(['status' => 'success', 'comics' => $result['comics'], 'pages' => $result['pages']]);
    }

    public function tag($name, $page)
    {
        $result = $this->repo->tag($name, $page);
        return response()->json(['status' => 'success', 'comics' => $result['comics'], 'pages' => $result['pages']]);
    }
}

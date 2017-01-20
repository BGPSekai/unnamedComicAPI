<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function type($name, $page)
    {
        $result = $this->repo->type($name, $page);
        return response()->json(['status' => 'success', 'comics' => $result['comics'], 'pages' => $result['pages']]);
    }

    public function tag(Request $request, $name, $page)
    {
        $fuzzy = filter_var($request->fuzzy, FILTER_VALIDATE_BOOLEAN);
        $result = $this->repo->tag($name, $page, $fuzzy);
        return response()->json(['status' => 'success', 'comics' => $result['comics'], 'pages' => $result['pages']]);
    }

    public function author($name, $page)
    {
        $result = $this->repo->author($name, $page);
        return response()->json(['status' => 'success', 'comics' => $result['comics'], 'pages' => $result['pages']]);
    }
}

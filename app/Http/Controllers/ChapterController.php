<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use JWTAuth;

class ChapterController extends Controller
{
    public function show(Request $request)
    {
    	$info = JWTAuth::decode($request->token);
    	$comic_id = $info->comic_id;
    	$chapter_id = $info->chapter_id;
    }
}

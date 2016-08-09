<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use JWTAuth;
use Storage;
use Response;

class ChapterController extends Controller
{
    public function show($img)
    {
    	$info = JWTAuth::decode(JWTAuth::getToken())->toArray();
    	$comic_id = $info['comic_id'];
    	$chapter_id = $info['chapter_id'];

    	$image_path = storage::files('comics/'.$comic_id.'/'.$chapter_id);
        return Response::download(storage_path().'/app/'.$image_path[$img-1]);
    }
}

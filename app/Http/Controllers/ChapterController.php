<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use JWTAuth;
use Storage;
use Response;

class ChapterController extends Controller
{
    public function show()
    {
        try {
            if (!JWTAuth::getToken())
                return response()->json(['msg' => 'error', 'msg' => 'A token is required.'], 400);

	    	$info = JWTAuth::decode(JWTAuth::getToken())->toArray();
        } catch (JWTException $e) {
            return response()->json(['status' => 'error', 'msg' => 'Invalid credentials.'], 500);
        }

    	$comic_id = $info['comic_id'];
    	$chapter_id = $info['chapter_id'];
    	$imgs = $info['imgs'];

    	if ($page < 1 || $page > $imgs)
    		return response()->json(['status' => 'error', 'msg' => 'Page does not exist.'], 404);

    	$image_path = storage::files('comics/'.$comic_id.'/'.$chapter_id);
        return Response::download(storage_path().'/app/'.$image_path[$page-1]);
    }
}

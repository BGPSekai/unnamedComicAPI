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
        try {
            if (!JWTAuth::getToken())
                return response()->json(['msg' => 'error', 'msg' => 'A token is required.']);

	    	$info = JWTAuth::decode(JWTAuth::getToken())->toArray();
        } catch (JWTException $e) {
            return response()->json(['status' => 'error', 'msg' => 'Invalid credentials.']);
        }

    	$comic_id = $info['comic_id'];
    	$chapter_id = $info['chapter_id'];
    	$imgs = $info['imgs'];

    	if ($imgs < 1 || $imgs > $img)
    		return response()->json(['status' => 'error', 'msg' => 'Page does not exist.']);

    	$image_path = storage::files('comics/'.$comic_id.'/'.$chapter_id);
        return Response::download(storage_path().'/app/'.$image_path[$img-1]);
    }
}

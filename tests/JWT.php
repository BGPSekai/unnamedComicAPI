<?php

use App\Entities\User;

class JWT
{
	public static function createToken($id)
	{
        $token = JWTAuth::fromUser(User::find($id));
        JWTAuth::setToken($token);
	}

	public static function clearToken()
	{
		JWTAuth::setToken('a.b.c');
	}
}
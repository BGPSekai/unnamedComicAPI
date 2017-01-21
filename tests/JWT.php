<?php

use App\Entities\User;

class JWT
{
	public static function createToken()
	{
        $token = JWTAuth::fromUser(User::first());
        JWTAuth::setToken($token);
	}

	public static function clearToken()
	{
		JWTAuth::setToken('a.b.c');
	}
}
<?php

class JWT
{
	public static function headers($user)
	{
	    $headers = ['Accept' => 'application/json'];

        $token = JWTAuth::fromUser($user);
        JWTAuth::setToken($token);
        $headers['Authorization'] = 'Bearer '.$token;

	    return $headers;
	}
}
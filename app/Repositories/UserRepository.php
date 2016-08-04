<?php

namespace App\Repositories;

use App\Entities\User;

class UserRepository
{
	public function create(array $data)
	{
		return 
	        User::create([
	            'name' => $data['name'],
	            'email' => $data['email'],
	            'password' => bcrypt($data['password']),
	        ]);;
	}
}

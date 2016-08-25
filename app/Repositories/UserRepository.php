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

	public function show($id)
	{
		return User::select('id', 'name')->find($id);
	}

	public function showDetail($id)
	{
		return User::find($id);
	}
}

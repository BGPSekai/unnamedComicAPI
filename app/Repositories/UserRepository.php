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
	            'from' => $data['from'],
	        ]);
	}

	public function show($id)
	{
		return User::select('id', 'name', 'avatar')->find($id);
	}

	public function showDetail($id)
	{
		return User::find($id);
	}

	public function avatar($id, $avatar)
	{
		return User::find($id)->update(['avatar' => $avatar]);
	}
}

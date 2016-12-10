<?php

namespace App\Repositories\Web;

use App\Entities\User;

class UserRepository
{
	public function index()
	{
		return User::paginate(10);
	}
}

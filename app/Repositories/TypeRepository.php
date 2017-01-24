<?php

namespace App\Repositories;

use App\Entities\Type;

class TypeRepository
{
	public function index()
	{
		return Type::pluck('id', 'name');
	}
}

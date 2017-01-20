<?php

namespace App\Repositories;

use App\Entities\Type;

class TypeRepository
{
	public function index()
	{
		return Type::select('id', 'name')->pluck('name');
	}
}

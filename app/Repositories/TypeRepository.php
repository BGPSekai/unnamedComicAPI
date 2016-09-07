<?php

namespace App\Repositories;

use App\Entities\Comic;
use App\Entities\Type;

class TypeRepository
{
	public function index()
	{
		return Type::select('id', 'name')->get();
	}

	public function show($id)
	{
		return Type::find($id)->name;
	}
}

<?php

namespace App\Repositories;

use App\Entities\Type;
use App\Entities\Comic;

class TypeRepository
{
	public function index()
	{
		return Type::get();
	}

	public function find($id, $page)
	{
		return
			Comic::where('type', '=', $id)->skip(($page - 1) * 10)->take(10)->get();
	}
}

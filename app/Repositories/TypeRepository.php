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

	public function find($id, $page)
	{
		return
			Comic::where('type', $id)->skip(($page - 1) * 10)->take(10)->get();
	}

	public function count($id)
	{
		return ceil(Comic::where('type', $id)->count()/10);
	}
}

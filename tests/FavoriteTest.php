<?php

class FavoriteTest extends TestCase
{
	public function testFavorite()
	{
		$this->get('/api/user/1/favorites')
			->seeJson(['favorites' => []]);

		$this->get('/api/user/2/favorites')
			->seeJson(['favorites' => []]);

		$this->get('/api/user/3/favorites')
			->seeJson(['favorites' => []]);
	}
}

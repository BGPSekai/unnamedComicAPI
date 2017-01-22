<?php

class FavoriteTest extends TestCase
{
	public function testFavorite()
	{
		$this->get('/api/user/1/favorites')
			->seeJson(['favorites' => []]);

		$this->get('/api/user/2/favorites')
			->assertResponseStatus(404);

		$this->post('/api/favorite/1')
			->assertResponseStatus(400);

		JWT::createToken();
		$this->post('/api/favorite/1')
			->seeJson(['favorites' => [1]]);

		$this->post('/api/favorite/2')
			->assertResponseStatus(404);

		$this->get('/api/user/1/favorites')
			->seeJson(['favorites' => [1]]);
	}
}

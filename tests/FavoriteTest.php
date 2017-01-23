<?php

class FavoriteTest extends TestCase
{
	public function testFavorite()
	{
		$this->get('/api/user/1/favorites')
			->seeJson(['favorites' => []]);

		$this->get('/api/user/3/favorites')
			->assertResponseStatus(404);

		$this->post('/api/favorite/1')
			->assertResponseStatus(400);

		JWT::createToken(1);
		$this->post('/api/favorite/1')
			->seeJson(['favorites' => [1]]);

		$this->post('/api/favorite/2')
			->assertResponseStatus(404);

		$this->post('/api/favorite/1')
			->assertResponseStatus(403);

		$this->get('/api/user/1/favorites')
			->seeJson(['favorites' => [1]]);
	}

	public function testDeleteFavorite()
	{
		$this->delete('/api/favorite/1')
			->assertResponseStatus(400);

		JWT::createToken(1);
		$this->delete('/api/favorite/1')
			->seeJson(['favorites' => []]);

		$this->delete('/api/favorite/1')
			->assertResponseStatus(404);

		$this->get('/api/user/1/favorites')
			->seeJson(['favorites' => []]);
	}
}

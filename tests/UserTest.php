<?php

class UserTest extends TestCase
{
	public function testUserInfo()
	{
		$this->get('/api/user/1')
			->assertResponseOk();

		$this->get('/api/user/3')
			->assertResponseStatus(404);

		$this->get('/api/user')
			->assertResponseStatus(400);

		JWT::createToken(1);
		$this->get('/api/user')
			->assertResponseOk();
	}

	public function testUpdateUserInfo()
	{
		JWT::clearToken();
		$this->post('/api/user', ['_method' => 'PATCH'])
			->assertResponseStatus(400);

		// JWT::createToken(1);
		// $this->post('/api/user', ['_method' => 'PATCH', 'name' => ''])
		// 	->assertResponseStatus(400);

		// $this->post('/api/user', ['_method' => 'PATCH', 'name' => 'name'])
		// 	->seeJson(['name' => 'name']);
	}
}

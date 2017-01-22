<?php

class UserTest extends TestCase
{
	public function testUserInfo()
	{
		$this->get('/api/user/1')
			->assertResponseOk();

		$this->get('/api/user/2')
			->assertResponseStatus(404);

		$this->get('/api/user')
			->assertResponseStatus(400);

		JWT::createToken();
		$this->get('/api/user')
			->assertResponseOk();
	}
}

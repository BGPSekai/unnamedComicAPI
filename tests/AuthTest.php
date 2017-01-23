<?php

require_once('JWT.php');

class AuthTest extends TestCase
{
	public function testRegister()
	{
		$user = $this->user();
		$user['password_confirmation'] = 'password';

		$this->post('api/auth/register', $user)
			->seeJson(['message' => ['The password confirmation does not match.']]);

		$user['password_confirmation'] = 'secret';
		$this->post('/api/auth/register', $this->user())
			->seeJson([
				'name' => $user['name'],
				'email' => $user['email']
			]);

		$this->post('/api/auth/register', $this->user())
			->seeJson(['message' => ['The email has already been taken.']]);
	}

	public function testAuth()
	{
		$user = $this->user();

		$this->post('/api/auth', $user)
			->assertResponseOk();

		$user['password'] = 'password';
		$this->post('/api/auth', $user)
			->assertResponseStatus(401);
	}

	public function testSocial()
	{
		$user = [
			'email' => 'test@test.com',
			'password' => 'testtest',
			'from' => 'Google'
		];

		$this->post('/api/auth', $user)
			->assertResponseStatus(400);

		$user['name'] = 'test';
		$this->post('/api/auth', $user)
			->assertResponseOK();
	}

	public function testResetPassword()
	{
		$password = [
			'password' => 'password',
			'new_password' => 'password',
			'new_password_confirmation' => 'secret'
		];

		$this->post('/api/auth/reset')
			->assertResponseStatus(400);

		JWT::createToken(1);
		$this->post('/api/auth/reset', $password)
			->assertResponseStatus(401);

		$password['password'] = 'secret';
		$this->post('/api/auth/reset', $password)
			->assertResponseStatus(400);

		$password['new_password_confirmation'] = 'password';
		$this->post('/api/auth/reset', $password)
			->assertResponseOk();


		$user = $this->user();

		$this->post('/api/auth', $user)
			->assertResponseStatus(401);

		$user['password'] = 'password';
		$this->post('/api/auth', $user)
			->assertResponseOk();
	}

	private function user()
	{
		return [
			'name' => 'test',
			'email' => 'test@test.com',
			'password' => 'secret',
			'password_confirmation' => 'secret'
		];
	}
}

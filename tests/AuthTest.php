<?php

use App\Entities\User;

require_once('JWT.php');

class AuthTest extends TestCase
{
	public function testRegister()
	{
		$user = $this->user();
		$user['password_confirmation'] = 'password';

		$this->post('api/auth/register', $user)
			->seeJson([
				'status' => 'error',
				'message' => ['The password confirmation does not match.']
			]);

		$user['password_confirmation'] = 'secret';
		$this->post('/api/auth/register', $this->user())
			->seeJson([
				'status' => 'success',
				'name' => $user['name'],
				'email' => $user['email']
			]);

		$this->post('/api/auth/register', $this->user())
			->seeJson([
				'status' => 'error',
				'message' => ['The email has already been taken.']
			]);
	}

	public function testAuth()
	{
		$user = $this->user();

		$this->post('/api/auth', $user)
			->assertResponseOk();

		$user['password'] = 'password';
		$token = $this->post('/api/auth', $user)
			->assertResponseStatus(401);
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

		$headers = JWT::headers(User::first());
		$this->post('/api/auth/reset', $password, $headers)
			->assertResponseStatus(401);

		$password['password'] = 'secret';
		$this->post('/api/auth/reset', $password, $headers)
			->assertResponseStatus(400);

		$password['new_password_confirmation'] = 'password';
		$this->post('/api/auth/reset', $password, $headers)
			->assertResponseOk();


		$user = $this->user();

		$token = $this->post('/api/auth', $user)
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

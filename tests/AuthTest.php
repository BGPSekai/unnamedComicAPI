<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Entities\User;

require_once('JWT.php');

class AuthTest extends TestCase
{
	use DatabaseMigrations;

	public function register()
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
		$this->register();

		$user = $this->user();

		$this->post('/api/auth', $user)
			->assertResponseStatus(200);

		$user['password'] = 'password';
		$token = $this->post('/api/auth', $user)
			->assertResponseStatus(401);
	}

	public function testResetPassword()
	{

		$this->register();

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
			->assertResponseStatus(200);


		$user = $this->user();

		$token = $this->post('/api/auth', $user)
			->assertResponseStatus(401);

		$user['password'] = 'password';
		$this->post('/api/auth', $user)
			->assertResponseStatus(200);
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

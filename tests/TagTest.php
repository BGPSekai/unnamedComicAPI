<?php

class TagTest extends TestCase
{
	public function testTag()
	{
		$this->get('/api/comic/1')
			->seeJson(['tags' => []]);

		$this->get('/api/tag/te')
			->assertResponseStatus(400);

		$this->post('/api/tag/test/1')
			->assertResponseStatus(400);

		JWT::createToken(1);
		$this->get('/api/tag/te')
			->seeJson(['tags' => []]);

		$this->post('/api/tag/test/1')
			->seeJson(['tags' => [['name' => 'test']]]);

		$this->post('/api/tag/test/1')
			->assertResponseStatus(403);

		$this->get('/api/comic/1')
			->seeJson(['tags' => [['name' => 'test']]]);

		$this->get('/api/tag/te')
			->seeJson(['tags' => ['test']]);

		$this->get('/api/tag/ab')
			->seeJson(['tags' => []]);

		$this->post('/api/tag/test/2')
			->assertResponseStatus(400);
	}

	public function testUntag()
	{
		JWT::clearToken();
		$this->delete('/api/tag/test/1')
			->assertResponseStatus(400);

		JWT::createToken(1);
		$this->delete('/api/tag/test/1')
			->assertResponseOk();

		$this->delete('/api/tag/test/1')
			->assertResponseStatus(404);

		$this->get('/api/comic/1')
			->seeJson(['tags' => []]);

		$this->get('/api/tag/te')
			->seeJson(['tags' => []]);
	}
}

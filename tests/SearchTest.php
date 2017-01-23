<?php

class SearchTest extends TestCase
{
	public function testAuthor()
	{
		$this->get('/api/search/author/te/1')
			->seeJson(['pages' => 1]);

		$this->get('/api/search/author/te/2')
			->seeJson(['comics' => []]);

		$this->get('/api/search/author/ab/1')
			->seeJson(['pages' => 0]);
	}

	public function testName()
	{
		$this->get('/api/search/name/te/1')
			->seeJson(['pages' => 1]);

		$this->get('/api/search/name/te/2')
			->seeJson(['comics' => []]);

		$this->get('/api/search/name/ab/1')
			->seeJson(['pages' => 0]);
	}

	public function testPublisher()
	{
		$this->get('/api/search/publisher/1/1')
			->seeJson(['pages' => 1]);

		$this->get('/api/search/publisher/1/2')
			->seeJson(['comics' => []]);

		$this->get('/api/search/publisher/2/1')
			->seeJson(['pages' => 0]);
	}

	public function testTag()
	{
		JWT::createToken(1);
		$this->post('/api/tag/test/1');


		$this->get('/api/search/tag/test/1')
			->seeJson(['pages' => 1]);

		$this->get('/api/search/tag/test/2')
			->seeJson(['comics' => []]);

		$this->get('/api/search/tag/te/1')
			->seeJson(['pages' => 0]);

		$this->delete('/api/tag/test/1');
	}

	public function testType()
	{
		$this->get('/api/search/type/test/1')
			->seeJson(['pages' => 1]);

		$this->get('/api/search/type/test/2')
			->seeJson(['comics' => []]);

		$this->get('/api/search/type/ab/1')
			->seeJson(['pages' => 0]);	
	}
}

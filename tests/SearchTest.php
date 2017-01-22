<?php

class SearchTest extends TestCase
{
// |        | GET|HEAD | api/search/tag/{name}/{page}          |      | App\Http\Controllers\SearchController@tag            | api,cors          |
// |        | GET|HEAD | api/search/type/{name}/{page}         |      | App\Http\Controllers\SearchController@type           | api,cors          |

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
}

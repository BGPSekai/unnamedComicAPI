<?php

use App\Entities\Comic;

class CommentTest extends TestCase
{
	public function testComment()
	{
		$this->post('/api/comment')
			->assertResponseStatus(400);

		JWT::createToken(1);
		$this->post('/api/comment')
			->assertResponseStatus(400);

		$this->post('/api/comment', ['comment' => 'test'])
			->assertResponseStatus(400);

		$this->post('/api/comment', ['id' => 1])
			->assertResponseStatus(400);

		$this->post('/api/comment', ['comic_id' => 1])
			->assertResponseStatus(400);

		$this->post('/api/comment', ['chapter_id' => 1])
			->assertResponseStatus(400);

		$this->post('/api/comment', ['id' => 1, 'comment' => 'test'])
			->assertResponseStatus(400);

		$this->post('/api/comment', ['comic_id' => 1, 'chapter_id' => 1, 'comment' => 'test'])
			->assertResponseStatus(400);

		$this->get('/api/comment/comic/1/1')
			->seeJson(['pages' => 0]);

		$this->post('/api/comment', ['comic_id' => 1, 'comment' => 'test comic'])
			->assertResponseOk();

		$this->get('/api/comment/comic/1/1')
			->seeJson(['pages' => 1]);

		$this->get('/api/comment/chapter/1/1')
			->seeJson(['pages' => 0]);

		$this->post('/api/comment', ['chapter_id' => 1, 'comment' => 'test comic'])
			->assertResponseOk();

		$this->get('/api/comment/chapter/1/1')
			->seeJson(['pages' => 1]);
	}
}

<?php

use App\Entities\Comic;

class CommentTest extends TestCase
{
	public function testComment()
	{
		$this->post('/api/comment')
			->assertResponseStatus(400);

		JWT::createToken();
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

		$this->post('/api/comment', ['id' => 1, 'comment' => 'test'])->seeJson(['fuck'])
			->assertResponseStatus(400);

		$this->post('/api/comment', ['comic_id' => 1, 'comment' => 'test'])->seeJson(['fuck'])
			->assertResponseStatus(400);

		$this->post('/api/comment', ['chapter_id' => 1, 'comment' => 'test'])->seeJson(['fuck'])
			->assertResponseStatus(400);

    	Comic::create([
    		'name' => 'test',
    		'summary' => 'test',
    		'author' => 'test',
    		'types' => json_encode(['test', 'test']),
    		'published_by' => 1,
    	]);

    	Comic::destroy(2);
	}
}

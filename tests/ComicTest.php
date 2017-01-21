<?php

use App\Entities\User;

class ComicTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testPublishComic()
    {
		$headers = JWT::headers(User::first());
		$this->post('/api/publish', $this->comic(), $headers)
			->assertResponseStatus(400)
			->seeJson(['fuck']);
    }

    private function comic()
    {
    	return [
    		'name' => 'test',
    		'author' => 'test',
    		'summary' => 'test',
    		'type' => ['test', 'test'],
    		'cover' => ''
    	];
    }
}

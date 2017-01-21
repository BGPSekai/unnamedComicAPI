<?php

use App\Entities\Comic;

class ComicTest extends TestCase
{
    public function testComicInfo()
    {
    	$this->get('/api/comic/1')
    		->assertResponseStatus(404);

    	Comic::create([
    		'name' => 'test',
    		'summary' => 'test',
    		'author' => 'test',
    		'type' => json_encode(['test', 'test']),
    		'published_by' => 1,
    	]);

    	$this->get('/api/comic/1')
    		->seeJson([
    			'types' => ['test', 'test'],
    			'published_by' => ['id' => 1, 'name' => 'test'],
    			'chapters' => [],
    			'tags' => []
			]);
    }
}

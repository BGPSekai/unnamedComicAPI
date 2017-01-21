<?php

use App\Entities\Comic;

class ComicTest extends TestCase
{
    public function testComicInfo()
    {
    	$this->get('/api/comic/1')
    		->assertResponseStatus(404);

    	$comic = $this->comic();
    	Comic::create($comic);

    	$comic['published_by'] = ['id' => 1, 'name' => 'test'];
    	$comic['type'] = ['test', 'test'];
    	$this->get('/api/comic/1')
    		->seeJson($comic);
    }

    public function comic()
    {
    	return [
    		'name' => 'test',
    		'summary' => 'test',
    		'author' => 'test',
    		'type' => json_encode(['test', 'test']),
    		'published_by' => 1,
    	];
    }
}

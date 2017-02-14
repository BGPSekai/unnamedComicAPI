<?php

use App\Entities\Comic;
use App\Entities\Chapter;
use App\Entities\Type;

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
    		'types' => json_encode(['test', 'test']),
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

    public function testComicIndex()
    {
    	$this->get('/api/comic/page/1')
    		->seeJson([
    			'types' => ['test', 'test'],
    			'published_by' => ['id' => 1, 'name' => 'test'],
    			'tags' => [],
    			'pages' => 1
			]);
    }

    public function testChapter()
    {
        $this->post('api/publish/1', ['comic_id' => 1])
            ->assertResponseStatus(400);

        JWT::createToken(1);
        $this->post('api/publish/1', ['comic_id' => 1])
            ->seeJson(['message' => ['The name field is required.']])
            ->assertResponseStatus(400);

    	$this->post('api/publish/1',
                [
                    'name' => 'test',
                    'comic_id' => 1
                ])
            ->seeJson([
                'published_by' => ['id' => 1, 'name' => 'test']
            ]);

    	$this->get('/api/comic/1')
    		->seeJson([
    			'chapter_count' => 1
    		]);
    }

    public function testUpdate()
    {
        Type::create([
            'name' => 'test',
        ]);

        Type::create([
            'name' => 'test2',
        ]);

        JWT::createToken(1);
        $this->post('/api/comic/1', ['_method' => 'PATCH', 'summary' => 'summary', 'types' => ['1', '2']])
            ->seeJson([
                'summary' => 'summary',
                'types' => ['1', '2']
            ]);

        $this->post('/api/comic/1', ['_method' => 'PATCH', 'types' => ['3']])
            ->assertResponseStatus(400);

        $this->post('/api/comic/2', ['_method' => 'PATCH', 'summary' => 'summary'])
            ->assertResponseStatus(400);
    }
}

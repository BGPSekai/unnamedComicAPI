<?php

namespace App\Repositories;

use App\Entities\Comic;
use App\Entities\User;
use App\Entities\Type;
use App\Entities\Tag;

class SearchRepository
{
    public function __construct()
    {
        $this->limit_per_page = 20;
    }

    public function name($name, $page)
    {
        $comics = Comic::with('tags')
            ->with('user')
            ->where('name', 'LIKE', '%'.$name.'%')
            ->orderBy('id', 'desc')
            ->skip(($page - 1) * $this->limit_per_page)
            ->take($this->limit_per_page)
            ->get();

        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('name', 'LIKE', '%'.$name.'%')->count()/$this->limit_per_page);
        return $result;
    }

    public function publisher($user_id, $page)
    {
        $comics = Comic::with('tags')
            ->with('user')
            ->where('published_by', $user_id)
            ->orderBy('id', 'desc')
            ->skip(($page - 1) * $this->limit_per_page)
            ->take($this->limit_per_page)
            ->get();

        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('published_by', $user_id)->count()/$this->limit_per_page);
        return $result;
    }

    public function type($name, $page)
    {
        $comics = Comic::with('tags')
            ->with('user')
            ->where('types', 'LIKE', '%'.$name.'%')
            ->orderBy('id', 'desc')
            ->skip(($page - 1) * $this->limit_per_page)
            ->take($this->limit_per_page)
            ->get();

        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('types', 'LIKE', '%'.$name.'%')->count()/$this->limit_per_page);
        return $result;
    }

    public function tag($name, $page, $fuzzy)
    {
        $comics = $fuzzy ?
            Tag::where('name', 'LIKE', '%'.$name.'%') :
            Tag::where('name', $name);

        $comics = array_unique(
            $comics
                ->orderBy('comic_id', 'desc')
                ->pluck('comic_id')
                ->toArray()
        );

        $comics = Comic::with('tags')
            ->with('user')
            ->find(
                array_slice($comics, ($page - 1) * $this->limit_per_page, $this->limit_per_page)
            );

        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(count($comics)/$this->limit_per_page);
        return $result;
    }

    public function author($name, $page)
    {
        $comics = Comic::where('author', 'LIKE', '%'.$name.'%')
            ->orderBy('id', 'desc')
            ->skip(($page - 1) * $this->limit_per_page)
            ->take($this->limit_per_page)
            ->get();

        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('author', 'LIKE', '%'.$name.'%')->count()/$this->limit_per_page);
        return $result;
    }

    private function detail($comics)
    {
        foreach ($comics as $comic) {
            $comic->types = json_decode($comic->types);
            $comic->type = Type::select('id', 'name')->find($comic->type);
            $comic->published_by = $comic->user;
            unset($comic->user);
        }
        return $comics;
    }
}

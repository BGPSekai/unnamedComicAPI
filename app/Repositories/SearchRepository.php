<?php

namespace App\Repositories;

use App\Entities\Comic;
use App\Entities\User;
use App\Entities\Type;
use App\Entities\Tag;

class SearchRepository
{
    public function name($name, $page)
    {
        $comics = Comic::with('tags')
            ->with('user')
            ->where('name', 'LIKE', '%'.$name.'%')
            ->orderBy('id', 'desc')
            ->skip(($page - 1) * 20)
            ->take(20)
            ->get();

        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('name', 'LIKE', '%'.$name.'%')->count()/20);
        return $result;
    }

    public function publisher($user_id, $page)
    {
        $comics = Comic::with('tags')
            ->with('user')
            ->where('published_by', $user_id)
            ->orderBy('id', 'desc')
            ->skip(($page - 1) * 20)
            ->take(20)
            ->get();

        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('published_by', $user_id)->count()/20);
        return $result;
    }

    public function type($name, $page)
    {
        $comics = Comic::with('tags')
            ->with('user')
            ->where('types', 'LIKE', '%'.$name.'%')
            ->orderBy('id', 'desc')
            ->skip(($page - 1) * 20)
            ->take(20)
            ->get();

        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('types', 'LIKE', '%'.$name.'%')->count()/20);
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
                array_slice($comics, ($page - 1) * 20, 20)
            );

        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(count($comics)/20);
        return $result;
    }

    public function author($name, $page)
    {
        $comics = Comic::where('author', 'LIKE', '%'.$name.'%')->orderBy('id', 'desc')->skip(($page - 1) * 20)->take(20)->get();
        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('author', 'LIKE', '%'.$name.'%')->count()/20);
        return $result;
    }

    private function detail($comics)
    {
        foreach ($comics as $comic) {
            $comic->type = Type::select('id', 'name')->find($comic->type);
            // $comic->tags = Tag::where('comic_id', $comic->id)->pluck('name');
            // $comic->published_by = User::select('id', 'name')->find($comic->published_by);
            $comic->published_by = $comic->user;
            unset($comic->user);
        }
        return $comics;
    }
}

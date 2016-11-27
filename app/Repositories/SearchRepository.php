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
        $comics = Comic::where('name', 'LIKE', '%'.$name.'%')->orderBy('id', 'desc')->skip(($page - 1) * 10)->take(10)->get();
        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('name', 'LIKE', '%'.$name.'%')->count()/10);
        return $result;
    }

    public function publisher($user_id, $page)
    {
        $comics = Comic::where('publish_by', $user_id)->orderBy('id', 'desc')->skip(($page - 1) * 10)->take(10)->get();
        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('publish_by', $user_id)->count()/10);
        return $result;
    }

    public function type($id, $page)
    {
        $comics = Comic::where('type', $id)->orderBy('id', 'desc')->skip(($page - 1) * 10)->take(10)->get();
        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('type', $id)->count()/10);
        return $result;
    }

    public function tag($name, $page, $fuzzy)
    {
        $comics = $fuzzy ?
            Tag::where('name', 'LIKE', '%'.$name.'%') :
            Tag::where('name', $name);

        $comics = $comics->orderBy('id', 'desc')->skip(($page - 1) * 10)->take(10)->get();

        // foreach ($comics as $comic)
        //  $comic = Comic::find($comic->comic_id);
        foreach ($comics as $key => $comic)
            $comics[$key] = Comic::find($comic->comic_id);
        $result['comics'] = $this->detail($comics);
        $result['pages'] = $fuzzy ?
            ceil(Tag::where('name', $name)->count()/10) :
            ceil(Tag::where('name', 'LIKE', '%'.$name.'%')->count()/10);
        return $result;
    }

    public function author($name, $page)
    {
        $comics = Comic::where('author', 'LIKE', '%'.$name.'%')->orderBy('id', 'desc')->skip(($page - 1) * 10)->take(10)->get();
        $result['comics'] = $this->detail($comics);
        $result['pages'] = ceil(Comic::where('author', 'LIKE', '%'.$name.'%')->count()/10);
        return $result;
    }

    private function detail($comics)
    {
        foreach ($comics as $comic) {
            $comic->type = Type::select('id', 'name')->find($comic->type);
            $comic->tags = Tag::where('comic_id', $comic->id)->pluck('name');
            $comic->publish_by = User::select('id', 'name')->find($comic->publish_by);
        }
        return $comics;
    }
}

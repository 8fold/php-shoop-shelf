<?php

namespace Eightfold\ShoopExtras;

use Eightfold\Shoop\Shoop as EFShoop;

use Eightfold\ShoopExtras\{
    ESStore,
    ESMarkdown,
    ESPath,
    ESUri,
    ESUrl
};

class Shoop extends EFShoop
{
    static public function markdown($content, ...$extensions): ESMarkdown
    {
        return ESMarkdown::fold($content, ...$extensions);
    }

    static public function store($path): ESStore
    {
        return ESStore::fold($path);
    }

    static public function path($path): ESPath
    {
        return ESPath::fold($path);
    }

    static public function uri($uri)
    {
        return ESUri::fold($uri);
    }

    static public function url($url)
    {
        return ESUrl::fold($url);
    }
}

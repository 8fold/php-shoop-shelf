<?php

namespace Eightfold\ShoopExtras;

use Eightfold\Shoop\Shoop as EFShoop;

use Eightfold\ShoopExtras\{
    ESStore,
    ESMarkdown,
    ESPath,
    ESUri
};

class Shoop extends EFShoop
{
    static public function markdown($content): ESMarkdown
    {
        return ESMarkdown::fold($content);
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
}

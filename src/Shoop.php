<?php

namespace Eightfold\ShoopExtras;

use Eightfold\Shoop\Shoop as EFShoop;

use Eightfold\ShoopExtras\ESStore;
use Eightfold\ShoopExtras\ESMarkdown;

class Shoop extends EFShoop
{
    static public function store($path): ESStore
    {
        return ESStore::fold($path);
    }

    static public function markdown($content): ESMarkdown
    {
        return ESMarkdown::fold($content);
    }
}

<?php

namespace Eightfold\Shoop\FluentTypes;

use Eightfold\Shoop\FluentTypes\Contracts\Shooped;
use Eightfold\Shoop\FluentTypes\Contracts\ShoopedImp;

class ESDictionary implements Shooped
{
    use ShoopedImp;

    public function types(): array
    {
        return ["collection", "list", "dictionary"];
    }
}

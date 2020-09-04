<?php

namespace Eightfold\Shoop\FluentTypes;

use Eightfold\Shoop\FluentTypes\Contracts\Shooped;
use Eightfold\Shoop\FluentTypes\Contracts\ShoopedImp;

class ESInteger implements Shooped
{
    use ShoopedImp;

    public function types(): array
    {
        return ["number", "integer"];
    }
}

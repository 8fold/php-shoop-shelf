<?php

namespace Eightfold\Shoop\FluentTypes;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\FluentTypes\Contracts\Shooped;
use Eightfold\Shoop\FluentTypes\Contracts\ShoopedImp;

use Eightfold\Shoop\FluentTypes\Contracts\Addable;
use Eightfold\Shoop\FluentTypes\Contracts\AddableImp;

use Eightfold\Shoop\FilterContracts\Ordered;
use Eightfold\Shoop\FilterContracts\OrderedImp;

class ESArray implements Shooped, Addable, Ordered
{
    use ShoopedImp, AddableImp, OrderedImp;

    public function types(): array
    {
        return ["collection", "list", "array"];
    }

    public function isOrdered(): Foldable
    {
        return Shoop::this(true);
    }
}

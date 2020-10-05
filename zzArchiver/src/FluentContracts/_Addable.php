<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\FilterContracts\Addable as FilterAddable;

interface _Addable extends FilterAddable
{
    public function prepend($value): Foldable;

    public function append($value): Foldable;
}

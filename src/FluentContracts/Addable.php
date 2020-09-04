<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\FilterContracts\Addable as PipeAddable;

interface Addable extends PipeAddable
{
    public function prepend($value): Foldable;

    public function append($value): Foldable;
}

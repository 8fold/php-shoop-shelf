<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\Apply;

use Eightfold\Shoop\FilterContracts\AddableImp as PipeAddableImp;

trait _AddableImp
{
    use PipeAddableImp;

    public function prepend($value): Foldable
    {
        return Shoop::this(
            Apply::plus($value, 0)->unfoldUsing($this->main)
        );
    }

    public function append($value): Foldable
    {}
}

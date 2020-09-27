<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\FilterContracts\SubtractableImp as PipeSubtractibleImp;

use Eightfold\Shoop\Filter\From;

trait _SubtractableImp
{
    use PipeSubtractibleImp;

    public function minusFirst($length = 1)
    {
        return static::fold(
            MinusFirst::applyWith($length)->unfoldUsing($this->main)
        );
    }

    public function minusLast($length = 1)
    {
        // TODO: Accept and respond to Subtractable
        return static::fold(
            From::applyWith(-$length)->unfoldUsing($this->main)
        );
    }

    public function minusEmpties()
    {
        return static::fold(
            MinusUsing::applyWith("is_empty")->unfoldUsing($this->main)
        );
    }
}

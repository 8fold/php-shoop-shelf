<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Shoop\FilterContracts\ComparableImp as PipeComparableImp;

use Eightfold\Shoop\Apply;

use Eightfold\Shoop\FilterContracts\Falsifiable;

/**
 * TODO: Make extension of Shooped
 */
trait ComparableImp
{
    use PipeComparableImp;

    public function is($compare): Falsifiable
    {
        return ESBoolean::fold(
            Apply::is($compare)->unfoldUsing($this->main)
        );
    }

    public function isNot($compare): Falsifiable
    {
        return ESBoolean::fold(
            $this->is($compare)->reverse()
        );
    }

    public function isEmpty(): Falsifiable
    {
        return ESBoolean::fold(
            Apply::isEmpty()->unfoldUsing($this->main)
        );
    }

    public function isNotEmpty(): Falsifiable
    {
        return $this->isEmpty()->reverse();
    }

    public function isGreaterThan($compare): Falsifiable
    {
        return ESBoolean::fold(
            Apply::isGreaterThan($compare)->unfoldUsing($this->main)
        );
    }

    public function isGreaterThanOrEqualTo($compare): Falsifiable
    {
        return ESBoolean::fold(
            Apply::isGreaterThanOrEqualTo($compare)->unfoldUsing($this->main)
        );
    }

    public function isLessThan($compare): Falsifiable
    {
        return $this->isGreaterThanOrEqualTo($compare)->reverse();
    }

    public function isLessThanOrEqualTo($compare): Falsifiable
    {
        return $this->isGreaterThan($compare)->reverse();
    }
}

<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\FilterContracts\Comparable as PipeComparable;
use Eightfold\Shoop\FilterContracts\Falsifiable;

interface Comparable extends PipeComparable
{
    public function isNot($compare): Falsifiable;

    public function isNotEmpty(): Falsifiable;

    public function isLessThan($compare): Falsifiable;

    public function isLessThanOrEqualTo($compare): Falsifiable;
}

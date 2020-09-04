<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Foldable\Foldable;
use Eightfold\Foldable\FoldableImp;

use Eightfold\Shoop\FilterContracts\Countable;
use Eightfold\Shoop\FilterContracts\StringableImp;
use Eightfold\Shoop\FilterContracts\TupleableImp;
use Eightfold\Shoop\FilterContracts\FalsifiableImp;
use Eightfold\Shoop\FilterContracts\KeyableImp;
use Eightfold\Shoop\FilterContracts\CountableImp;

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\Apply;

use Eightfold\Shoop\FluentTypes\ESBoolean;

use Eightfold\Shoop\FluentTypes\ESArray;
use Eightfold\Shoop\FluentTypes\ESDictionary;
use Eightfold\Shoop\FluentTypes\ESInteger;
use Eightfold\Shoop\FluentTypes\ESString;
use Eightfold\Shoop\FluentTypes\ESJson;
use Eightfold\Shoop\FluentTypes\ESTuple;

use Eightfold\Shoop\FluentTypes\Contracts\ComparableImp;
use Eightfold\Shoop\FluentTypes\Contracts\ReversibleImp;
use Eightfold\Shoop\FluentTypes\Contracts\SubtractableImp;
use Eightfold\Shoop\FluentTypes\Contracts\ArrayableImp;

trait ShoopedImp
{
    use FoldableImp, FalsifiableImp, SubtractableImp, ReversibleImp,
        TupleableImp, StringableImp, ArrayableImp, KeyableImp, CountableImp,
        ComparableImp;

    public function __construct($main)
    {
        $this->main = $main;
    }

    public function asArray(
        $start = 0,
        bool $includeEmpties = true,
        int $limit = PHP_INT_MAX
    ): Foldable
    {
        return ESArray::fold(
            Apply::typeAsArray($start, $includeEmpties, $limit)
                ->unfoldUsing($this->main)
        );
    }

    public function asBoolean(): Foldable
    {
        if (is_a($this, ESBoolean::class)) {
            return ESBoolean::fold($this->main);
        }
        return ESBoolean::fold(
            Apply::typeAsBoolean()->unfoldUsing($this->main)
        );
    }

    public function asDictionary(
        $start = 0,
        $includeEmpties = true,
        int $limit = PHP_INT_MAX
    ): Foldable
    {
        return ESDictionary::fold(
            Apply::typeAsDictionary($start, $includeEmpties, $limit)
                ->unfoldUsing($this->main)
        );
    }

    public function asInteger(): Foldable
    {
        return ESInteger::fold(
            Apply::typeAsInteger()->unfoldUsing($this->main)
        );
    }

    public function asJson(): Foldable
    {
        return ESJson::fold(
            Apply::typeAsJson()->unfoldUsing($this->main)
        );
    }

    public function asString(string $glue = ""): Foldable
    {
        return ESString::fold(
            Apply::typeAsString($glue)->unfoldUsing($this->main)
        );
    }

    public function asTuple(): Foldable
    {
        return ESTuple::fold(
            Apply::typeAsTuple()->unfoldUsing($this->main)
        );
    }

    public function random($limit = 1)
    {
        return Shoop::this(
            Apply::random($limit)->unfoldUsing($this->main)
        );
    }
}

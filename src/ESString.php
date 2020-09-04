<?php
declare(strict_types=1);

namespace Eightfold\Shoop\FluentTypes;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\FluentTypes\Contracts\Shooped;
use Eightfold\Shoop\FluentTypes\Contracts\ShoopedImp;

use Eightfold\Shoop\Filter\Plus;
use Eightfold\Shoop\Filter\TypeAsArray;

class ESString implements Shooped
{
    use ShoopedImp;

    public function types(): array
    {
        return ["string"];
    }

// -> Rearrange
    // TODO: PHP 8.0 - bool|ESBoolean
    public function reverse($preserveMembers = true)
    {
        $string = Php::stringReversed($this->main);
        return static::fold($string);
    }

// -> Math operations
    // TODO: PHP 8.0 - string|ESString
    public function plus(...$terms): ESString
    {
        $string = Shoop::pipe($this->main, Plus::applyWith(...$terms))
            ->unfold();
        return static::fold($string);
    }

    // TODO: PHP 8.0 - int|ESInteger
    public function multiply($multiplier = 1)
    {
        $string = Php::stringRepeated($this->main, $multiplier);
        return static::fold($string);
    }

    // TODO: PHP 8.0 - string|int|ESString|ESInteger, bool|ESBoolean, int|ESInteger
    public function divide(
        $divisor = 0,
        $includeEmpties = true,
        $limit = PHP_INT_MAX
    ): ESArray
    {
        return Shoop::this(
            TypeAsArray::applyWith($divisor, $includeEmpties, $limit)
                ->unfoldUsing($this->main)
        );
    }

// -> Replacements
    // TODO: PHP 8.0 array|ESDictionary = $replacements bool|ESBoolean = $caseSensitive
    public function replace($replacements = [], $caseSensitive = true): ESString
    {
        $needles = array_keys($replacements);
        $replacements = array_values($replacements);
        $string = Php::stringAfterReplacing($this->main, $replacements, $caseSensitive);
        return static::fold($string);
    }
}

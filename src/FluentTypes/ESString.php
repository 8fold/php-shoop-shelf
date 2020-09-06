<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Shoop\Shooped;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\Apply;

use Eightfold\Shoop\FilterContracts\Interfaces\Arrayable;

class ESString extends Shooped
{
    public function main()
    {
        return static::fold($this->main);
    }

    public function asArray(
        $start = 0, // int|string
        bool $includeEmpties = true,
        int $limit = PHP_INT_MAX
    ): Arrayable
    {
        $main  = $this->main()->unfold();
        $array = Apply::TypeAsArray($start, $includeEmpties, $limit)
            ->unfoldUsing($main);
        return Shoop::array($array);
    }

    /**
     * @deprecated maybe ??
     */
    public function divide(
        $start = 0, // int|string
        bool $includeEmpties = true,
        int $limit = PHP_INT_MAX
    ): ESArray
    {
        return $this->asArray($start, $includeEmpties, $limit);
    }

    public function prepend($content): ESString
    {
        $main = $this->main();
        $string = Apply::prepend($content)->unfoldUsing($main);
        return Shoop::string($stirng);
    }

    public function markdown(): ESMarkdown
    {
        $content = $this->main();
        return ESMarkdown::fold($content);
    }

// -> Rearrange
    // TODO: PHP 8.0 - bool|ESBoolean
    // public function reverse($preserveMembers = true)
    // {
    //     $string = Php::stringReversed($this->main);
    //     return static::fold($string);
    // }

// -> Math operations
    // TODO: PHP 8.0 - string|ESString
    // public function plus(...$terms): ESString
    // {
    //     $string = Shoop::pipe($this->main, Plus::applyWith(...$terms))
    //         ->unfold();
    //     return static::fold($string);
    // }

    // TODO: PHP 8.0 - int|ESInteger
    // public function multiply($multiplier = 1)
    // {
    //     $string = Php::stringRepeated($this->main, $multiplier);
    //     return static::fold($string);
    // }

    // TODO: PHP 8.0 - string|int|ESString|ESInteger, bool|ESBoolean, int|ESInteger
    // public function divide(
    //     $divisor = 0,
    //     $includeEmpties = true,
    //     $limit = PHP_INT_MAX
    // ): ESArray
    // {
    //     return Shoop::this(
    //         TypeAsArray::applyWith($divisor, $includeEmpties, $limit)
    //             ->unfoldUsing($this->main)
    //     );
    // }

    public function trim(): ESString
    {
        $main   = $this->main()->unfold();
        $string = Shoop::pipe($main,
            Apply::minus([" ", "\t", "\n", "\r", "\0", "\x0B"], false, false)
        )->unfold();

        return static::fold($string);
    }

    public function replace($replacements = [], $caseSensitive = true): ESString
    {
        $main         = $this->main();
        $needles      = array_keys($replacements);
        $replacements = array_values($replacements);
        $string       = str_replace($needles, $replacements, $main);
// var_dump(__FILE__);
// var_dump(__LINE__);
// die(var_dump(
//     static::fold($string)
// ));
        return static::fold($string);
    }
}

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

    // public function trim(): ESString
    // {
    //     $main   = $this->main()->unfold();
    //     $string = Shoop::pipe($main,
    //         Apply::minus([" ", "\t", "\n", "\r", "\0", "\x0B"], false, false)
    //     )->unfold();

    //     return static::fold($string);
    // }

    // public function replace($replacements = [], $caseSensitive = true): ESString
    // {
    //     $main         = $this->main();
    //     $needles      = array_keys($replacements);
    //     $replacements = array_values($replacements);
    //     $string       = str_replace($needles, $replacements, $main);
    //     return static::fold($string);
    // }
}

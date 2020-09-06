<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Shoop\Shooped;

use Eightfold\Foldable\Foldable;
use Eightfold\Foldable\FoldableImp;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\Apply;

class ESArray extends Shooped
{
    public function main(): ESArray
    {
        return Shoop::array($this->main);
    }

    public function join($glue = ""): ESString
    {
        $main = $this->main()->unfold();
        $string = Apply::typeAsString($glue)->unfoldUsing($main);

        return Shoop::string($string);
    }

    public function minusLast($length = 1): ESArray
    {
        $main  = $this->main()->unfold();
        $array = Apply::from(0, -$length)->unfoldUsing($main);

        return Shoop::array($array);
    }

    public function first($length = 1)
    {
        $main  = $this->main()->unfold();
        $value = Apply::from(0, $length)->unfoldUsing($main);
        if ($length === 1) {
            if (is_string($value[0]) and $first = $value[0]) {
                return Shoop::string($first);
            }
        }
        return static::fold($value);
    }

    public function last($length = 1)
    {
        $main  = $this->main()->unfold();
        $start = $this->main()->asInteger()->minus($length)->unfold();
        $value = Apply::from($start, $length)->unfoldUsing($main);
        if ($length === 1) {
            if (is_string($value[0]) and $first = $value[0]) {
                return Shoop::string($first);
            }
        }
        return static::fold($value);
    }
}

<?php

namespace Eightfold\ShoopExtras;

use Eightfold\Shoop\Interfaces\Foldable;
use Eightfold\Shoop\Traits\FoldableImp;

use Eightfold\Shoop\{
    Helpers\Type,
    ESString,
    ESArray,
    ESBool
};

use Eightfold\ShoopExtras\{
    Shoop,
    ESInt,
    ESStore
};

class ESPath implements Foldable
{
    use FoldableImp;

    static public function processedMain($main)
    {
        return Type::sanitizeType($main, ESString::class)->unfold();
    }

    private function delimiter()
    {
        return (isset($this->args[0])) ? $this->args[0] : "/";
    }

    // public function store(): ESStore
    // {
    //     return Shoop::store($this->value());
    // }

    public function plus(...$parts)
    {
        $path = $this->array()->plus(...$parts)->join("/")->start("/");
        return static::fold($path);
    }

    public function dropLast($length = 1)
    {
        $length = Type::sanitizeType($length, ESInt::class)->unfold();
        $path = $this->array()->dropLast($length)->join("/")->start("/");
        return static::fold($path);
    }

    public function parts()
    {
        return $this->string()->divide($this->delimiter(), false)->reindex();
    }

    public function string(): ESString
    {
        return Shoop::string($this->main());
    }

    public function array(): ESArray
    {
        return $this->parts();
    }

    // public function noEmpties()
    // {
    //     $path = $this->parts()->join("/")->start("/");
    //     return satic::fold($path);
    // }
}

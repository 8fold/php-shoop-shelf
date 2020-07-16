<?php

namespace Eightfold\ShoopExtras;

use Eightfold\Shoop\Helpers\Type;
use Eightfold\Shoop\Interfaces\Shooped;
use Eightfold\Shoop\Traits\ShoopedImp;
use Eightfold\Shoop\{
    ESString,
    ESArray,
    ESBool
};

use Eightfold\ShoopExtras\{
    Shoop,
    ESStore
};

class ESPath implements Shooped
{
    use ShoopedImp;

    protected $delimiter = "/";

    public function __construct($path)
    {
        $this->value = Type::sanitizeType($path, ESString::class)->unfold();
    }

    public function delimiter($delimiter = "/")
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    public function string(): ESString
    {
        return Shoop::string($this->value());
    }

    public function parts()
    {
        return $this->string()->divide($this->delimiter, false)->reindex();
    }

    public function array(): ESArray
    {
        return $this->parts();
    }

    public function store(): ESStore
    {
        return Shoop::store($this->value());
    }

    public function plus(...$parts)
    {
        $path = $this->parts()->plus(...$parts)->join("/")->start("/");
        return static::fold($path);
    }

    public function dropLast($length = 1)
    {
        $path = $this->parts()->dropLast($length)->join("/")->start("/");
        return static::fold($path);
    }

    public function noEmpties()
    {
        $path = $this->parts()->join("/")->start("/");
        return satic::fold($path);
    }
}

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

use Eightfold\ShoopExtras\Shoop;

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

    public function parent($length = 1)
    {
        return $this->dropLast()->isFolder(function($result, $path) {
            return ($result)
                ? Shoop::store($path)
                : Shoop::store($path)->parent();
        });
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

    public function plus(...$parts)
    {
        $path = $this->parts()->plus(...$parts)->join("/")->start("/");
        return Shoop::store($path);
    }

    public function dropLast($length = 1)
    {
        $path = $this->parts()->dropLast($length)->join("/")->start("/");
        return Shoop::store($path);
    }

    public function noEmpties()
    {
        $path = $this->parts()->join("/")->start("/");
        return Shoop::string($path);
    }
}

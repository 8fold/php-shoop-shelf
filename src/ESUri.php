<?php

namespace Eightfold\ShoopExtras;

use Eightfold\ShoopExtras\ESPath;

use Eightfold\Shoop\Helpers\Type;
use Eightfold\Shoop\Interfaces\Shooped;
use Eightfold\Shoop\Traits\ShoopedImp;
use Eightfold\Shoop\{
    ESString,
    ESArray,
    ESBool
};

use Eightfold\ShoopExtras\Shoop;

class ESUri extends ESPath
{
    private $raw = "";
    private $protocolDelimiter = "://";

    public function __construct($path)
    {
        $this->raw = $path;
    }

    public function value()
    {
        return Shoop::string($this->raw())
            ->divide($this->protocolDelimiter, false, 2)->last()
            ->divide($this->delimiter, false, 2)
            ->countIsGreaterThan(1, function($result, $path) {
                return ($result->unfold() or $this->protocol()->isEmpty)
                    ? $path->last()->start("/")
                    : Shoop::string("/");
            });
    }

    public function tail()
    {
        return $this->value();
    }

    public function parts()
    {
        return $this->tail()->divide($this->delimiter, false)->reindex();
    }

    private function raw()
    {
        return $this->raw;
    }

    public function protocolDelimiter($delimiter = "://")
    {
        $this->protocolDelimiter = $delimiter;
        return $this;
    }

    public function protocol()
    {
        return Shoop::string($this->raw())
            ->divide($this->protocolDelimiter, false, 2)
            ->countIsGreaterThan(1, function($result, $array) {
                return ($result->unfold())
                    ? $array->first()
                    : Shoop::string("");
            });
    }

    public function domain()
    {
        return Shoop::string($this->raw())
            ->divide($this->protocolDelimiter, false, 2)->last()
            ->divide($this->delimiter, false, 2)->first();
    }

    public function unfold()
    {
        return $this->value();
    }
}

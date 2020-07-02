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
    private $protocolDelimiter = "://";
    private $domain = "";

    public function protocol()
    {
        return Shoop::string($this->value())
            ->divide($this->protocolDelimiter, false, 2)->first();
    }

    public function domain()
    {
        return Shoop::string($this->value())
            ->divide($this->protocolDelimiter, false, 2)->last()
            ->divide($this->delimiter, false, 2)->first();
    }

    public function tail()
    {
        return Shoop::string($this->value())
            ->divide($this->protocolDelimiter, false, 2)->last()
            ->divide($this->delimiter, false, 2)->last()->start($this->delimiter);
    }
}

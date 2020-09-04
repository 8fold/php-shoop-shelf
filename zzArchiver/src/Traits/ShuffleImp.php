<?php

namespace Eightfold\Shoop\FluentTypes\Traits;



use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\FluentTypes\{
    ESArray,
    ESBoolean,
    ESInteger,
    ESString,
    ESTuple,
    ESJson,
    ESDictionary
};

trait ShuffleImp
{
    public function shuffle(): Foldable
    {
        $array = $this->arrayUnfolded();
        shuffle($array);
        if (Type::is($this, ESArray::class)) {
            return Shoop::array($array);

        } elseif (Type::is($this, ESString::class)) {
            $string = implode("", $array);
            return Shoop::string($string);

        }
    }
}

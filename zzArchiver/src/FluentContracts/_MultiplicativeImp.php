<?php

namespace Eightfold\Shoop\FluentTypes\Traits;

use Eightfold\Shoop\Foldable\Foldable;

use Eightfold\Shoop\FluentTypes\Helpers\PhpIndexedArray;
use Eightfold\Shoop\FluentTypes\Helpers\PhpAssociativeArray; // TODO: Use facade
use Eightfold\Shoop\FluentTypes\Helpers\PhpObject;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\FluentTypes\ESArray;
use Eightfold\Shoop\FluentTypes\ESBoolean;
use Eightfold\Shoop\FluentTypes\ESInteger;
use Eightfold\Shoop\FluentTypes\ESString;
use Eightfold\Shoop\FluentTypes\ESTuple;
use Eightfold\Shoop\FluentTypes\ESJson;
use Eightfold\Shoop\FluentTypes\ESDictionary;

trait _MultiplicativeImp
{
    public function multiply($multiplier = 1): Foldable
    {
        if (Type::is($this, ESArray::class, ESDictionary::class, ESJson::class, ESTuple::class)) {
            $product = [];
            for ($i = 0; $i < $multiplier; $i++) {
                $product[] = $this;
            }
            return Shoop::array($product);

        } elseif (Type::is($this, ESInteger::class)) {
            $int = $this->intUnfolded();
            $multiplier = Type::sanitizeType($multiplier, ESInteger::class)->unfold();
            $product = $int * $multiplier;
            return Shoop::integer($product);

        } elseif (Type::is($this, ESString::class)) {
            // $string = $this->stringUnfolded();
            // $multiplier = Type::sanitizeType($multiplier, ESInteger::class)->unfold();
            // $repeated = str_repeat($string, $multiplier);

            // return Shoop::string($repeated);

        }
    }
}

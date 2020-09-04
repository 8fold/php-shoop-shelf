<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Shoop\Filter\Reversed;

use Eightfold\Shoop\FluentTypes\Contracts\Shooped;

use Eightfold\Shoop\FluentTypes\Helpers\PhpIndexedArray;
use Eightfold\Shoop\FluentTypes\Helpers\PhpAssociativeArray;
use Eightfold\Shoop\FluentTypes\Helpers\PhpObject;
use Eightfold\Shoop\FluentTypes\Helpers\PhpString;

use Eightfold\Shoop\FluentTypes\ESArray;
use Eightfold\Shoop\FluentTypes\ESBoolean;
use Eightfold\Shoop\FluentTypes\ESInteger;
use Eightfold\Shoop\FluentTypes\ESString;
use Eightfold\Shoop\FluentTypes\ESTuple;
use Eightfold\Shoop\FluentTypes\ESJson;
use Eightfold\Shoop\FluentTypes\ESDictionary;

trait ReversibleImp
{
    public function reverse(): Shooped
    {
        return static::fold(
            Reversed::apply()->unfoldUsing($this->unfold())
        );
    }
}

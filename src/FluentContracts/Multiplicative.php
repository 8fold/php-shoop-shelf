<?php

namespace Eightfold\Shoop\FluentTypes\Interfaces;

use Eightfold\Shoop\FluentTypes\ESInteger;
use Eightfold\Shoop\Foldable\Foldable;

interface Multiplicative
{
    public function multiply($int): Foldable;
}

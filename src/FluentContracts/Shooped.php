<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\FluentTypes\Contracts\Comparable;

use Eightfold\Shoop\FilterContracts\Arrayable;
use Eightfold\Shoop\FilterContracts\Subtractable;
use Eightfold\Shoop\FilterContracts\Countable;
use Eightfold\Shoop\FilterContracts\Falsifiable;
use Eightfold\Shoop\FilterContracts\Keyable;
use Eightfold\Shoop\FilterContracts\Stringable;
use Eightfold\Shoop\FilterContracts\Tupleable;
use Eightfold\Shoop\FilterContracts\Typeable;

interface Shooped extends
    Foldable,
    Arrayable,
    Comparable,
    Subtractable,
    Countable,
    Falsifiable,
    Keyable,
    Stringable,
    Tupleable,
    Typeable
{
    public function __construct($main);

    // TODO: PHP 8.0 int|ESInteger -> Shooped|object|callable
    public function random($limit = 1);
}

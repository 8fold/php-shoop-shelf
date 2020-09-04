<?php

namespace Eightfold\Shoop\FluentTypes\Interfaces;

use Eightfold\Shoop\FluentTypes\ESBoolean;

interface Wrap
{
    // Does not make sense on ESBoolean, ESInteger
    public function first();

    public function last();

    public function start(...$prefixes);

    public function end(...$suffixes);

    // TODO: PHP 8.0 uncomment
    // public function startsWith(string|array $needles, \Closure $closure = null)
    public function startsWith(...$needles);

    // public function doesNotStartWith(string|array $needles, \Closure $closure = null)
    public function doesNotStartWith(...$needles): ESBoolean;

    // public function endsWith(string|array $needles, \Closure $closure = null)
    public function endsWith(...$needles);

    // public function doesNotEndWith(string|array $needles, \Closure $closure = null)
    public function doesNotEndWith(...$needles): ESBoolean;
}

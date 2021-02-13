<?php

namespace Eightfold\Json\Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    protected function assertEquality($expected, $result)
    {
       $this->assertTrue($result == $expected, $expected ."\n\n". $result);
    }
}

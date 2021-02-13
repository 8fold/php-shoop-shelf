<?php
namespace Eightfold\Clip\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\Clip\Command;

class ProcessTest extends TestCase
{
    public function testCanEchoFoo()
    {
        $result = Command::open('echo')
            ->extra('foo')
            ->close();

        $this->assertEquals('foo', trim($result->content));
    }

    public function testCanGetWhichPhp()
    {
        $result = Command::open('which')
            ->extra('php')
            ->close();

        $this->assertEquals('/usr/local/bin/php', trim($result->content));
    }
}

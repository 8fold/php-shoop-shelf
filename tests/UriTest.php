<?php

namespace Eightfold\ShoopExtras\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\ShoopExtras\{
    Shoop,
    ESUri
};

class UriTest extends TestCase
{
    public function testCanDoAllTheThings()
    {
        $path = "https://8fold.pro/data/inner-folder";

        $actual = new ESUri($path);
        $this->assertNotNull($actual);

        $actual = Shoop::uri($path)->protocol;
        $this->assertEquals("https", $actual);

        $actual = Shoop::uri($path)->domain;
        $this->assertEquals("8fold.pro", $actual);

        $actual = Shoop::uri($path)->tail;
        $this->assertEquals("/data/inner-folder", $actual);

        $actual = Shoop::uri($path)->parts;
        $this->assertEquals(["data", "inner-folder"], $actual);

        $path = "/data/inner-folder";
        $expected = 2;
        $actual = Shoop::uri($path)->parts()->count();
        $this->assertEquals($expected, $actual->unfold());

        $path = "/";
        $expected = 0;
        $actual = Shoop::uri($path)->parts()->count();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testCanHandleRoot()
    {
        // "http://localhost" - root
        $path = "http://localhost";
        $expected = "/";
        $actual = Shoop::uri($path);
        $this->assertEquals($expected, $actual->unfold());

        // "http://localhost/" - root
        $path = "http://localhost/";
        $expected = "/";
        $actual = Shoop::uri($path);
        $this->assertEquals($expected, $actual->unfold());

        // "http://localhost/sub"
        $path = "http://localhost/sub";
        $expected = "/sub";
        $actual = Shoop::uri($path);
        $this->assertEquals($expected, $actual->unfold());

        // "http://localhost/sub/sub"
        $path = "http://localhost/sub/sub";
        $expected = "/sub/sub";
        $actual = Shoop::uri($path);
        $this->assertEquals($expected, $actual->unfold());

        // "/" - root
        $path = "/";
        $expected = "/";
        $actual = Shoop::uri($path);
        $this->assertEquals($expected, $actual->unfold());

        // "/sub"
        $path = "/sub";
        $expected = "/sub";
        $actual = Shoop::uri($path);
        $this->assertEquals($expected, $actual->unfold());
    }
}

<?php

namespace Eightfold\ShoopExtras\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\ShoopExtras\{
    Shoop,
    ESPath
};

class PathTest extends TestCase
{
    public function testPathValues()
    {
        $path = __DIR__ ."/data/inner-folder";
        $actual = new ESPath($path);
        $this->assertNotNull($actual);

        $actual = ESPath::fold($path);
        $this->assertNotNull($actual);

        $actual = ESPath::fold($path)->value;
        $this->assertEquals($path, $actual);
    }

    public function testCanAddParts()
    {
        $base = __DIR__;
        $expected = $base ."/data/inner-folder";
        $actual = ESPath::fold($base)->plus("data", "inner-folder")->value();
        $this->assertSame($expected, $actual);
    }

    public function testDropParts()
    {
        $base = __DIR__ ."/data/inner-folder";
        $expected = __DIR__;
        $actual = ESPath::fold($base)->dropLast(2)->value();
        $this->assertSame($expected, $actual);
    }

    public function testArray()
    {
        $base = "/root/data/inner-folder";
        $expected = ["root", "data", "inner-folder"];
        $actual = ESPath::fold($base)->array();
        $this->assertEquals($expected, $actual->unfold());

        $expected = ["root", "data", "inner-folder"];
        $actual = ESPath::fold($base)->parts();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testParent()
    {
        $base = __DIR__ ."/data/inner-folder/subfolder/inner.md";
        $expected = __DIR__ ."/data/inner-folder/subfolder";
        $actual = ESPath::fold($base)->parent();
        $this->assertSame($expected, $actual->unfold());

        $base = __DIR__ ."/data/inner-folder/subfolder";
        $expected = __DIR__ ."/data/inner-folder";
        $actual = ESPath::fold($base)->parent();
        $this->assertSame($expected, $actual->unfold());
    }

    public function testCanUnfold()
    {
        $expected = Shoop::string(__DIR__)->divide("/")->dropLast()
            ->plus("src", "Routes", "any.php")->join("/");
        $actual = Shoop::path(__DIR__)->dropLast()
            ->plus("src", "Routes", "any.php");
        $this->assertSame($expected->unfold(), $actual->unfold());
    }
}

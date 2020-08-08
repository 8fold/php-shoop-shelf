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

        $actual = ESPath::fold($path)->main;
        $this->assertEquals($path, $actual);

        $expected = [
            "oasis",
            "names",
            "specification",
            "docbook",
            "dtd",
            "xml",
            "4.1.2"
        ];
        $actual = ESPath::fold(
            "oasis:names:specification:docbook:dtd:xml:4.1.2",
            ":"
        )->parts;
        $this->assertSame($expected, $actual);
    }

    public function testCanAddParts()
    {
        $base = __DIR__;
        $expected = $base ."/data/inner-folder";
        $actual = ESPath::fold($base)->plus("data", "inner-folder")->main();
        $this->assertSame($expected, $actual);
    }

    public function testDropParts()
    {
        $base = __DIR__ ."/data/inner-folder";
        $expected = __DIR__;
        $actual = ESPath::fold($base)->dropLast(2)->main();
        $this->assertSame($expected, $actual);
    }

    public function testArray()
    {
        $base = "/root/data/inner-folder";
        $expected = ["root", "data", "inner-folder"];
        $actual = ESPath::fold($base)->array;
        $this->assertEquals($expected, $actual);

        $expected = ["root", "data", "inner-folder"];
        $actual = ESPath::fold($base)->parts;
        $this->assertEquals($expected, $actual);
    }

    public function testParent()
    {
        $base = __DIR__ ."/data/inner-folder/subfolder/inner.md";
        $expected = __DIR__ ."/data/inner-folder/subfolder";
        $actual = ESPath::fold($base)->dropLast;
        $this->assertSame($expected, $actual);

        $base = __DIR__ ."/data/inner-folder/subfolder";
        $expected = __DIR__ ."/data/inner-folder";
        $actual = ESPath::fold($base)->dropLast;
        $this->assertSame($expected, $actual);
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

<?php

namespace Eightfold\ShoopExtras\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\ShoopExtras\{
    Shoop,
    ESStore
};

class StoreTest extends TestCase
{
    public function testCanCheckPathIsFileOrFolder()
    {
        $path = __DIR__ ."/data/inner-folder";
        $actual = new ESStore($path);
        $this->assertNotNull($actual);

        $actual = ESStore::fold($path);
        $this->assertNotNull($actual);

        $actual = ESStore::fold($path)->value;
        $this->assertEquals($path, $actual);

        $actual = ESStore::fold($path)->isFolder;
        $this->assertTrue($actual);

        $path = $path ."/content.md";
        $actual = ESStore::fold($path)->isFile;
        $this->assertTrue($actual);
    }

    public function testCanGetContents()
    {
        $path = __DIR__ ."/data";

        $expected = [
            __DIR__ ."/data/inner-folder",
            __DIR__ ."/data/link.md",
            __DIR__ ."/data/table.md",
        ];
        $actual = ESStore::fold($path)->content;
        $this->assertSame($expected, $actual);

        $path = $path ."/inner-folder/content.md";
        $expected = "Hello, World!";
        $actual = ESStore::fold($path)->content;
        $this->assertSame($expected, $actual);
    }

    public function testCanGetFilesAndFolders()
    {
        $path = __DIR__ ."/data/inner-folder";
        $expected = [__DIR__ ."/data/inner-folder/subfolder"];
        $actual = ESStore::fold($path)->folders;
        $this->assertSame($expected, $actual);

        $path = __DIR__ ."/data/inner-folder";
        $expected = [__DIR__ ."/data/inner-folder/content.md"];
        $actual = ESStore::fold($path)->files;
        $this->assertSame($expected, $actual);
    }

    public function testCanAddParts()
    {
        $base = __DIR__;
        $expected = $base ."/data/inner-folder";
        $actual = ESStore::fold($base)->plus("data", "inner-folder")->value();
        $this->assertSame($expected, $actual);
    }

    public function testDropParts()
    {
        $base = __DIR__ ."/data/inner-folder";
        $expected = __DIR__;
        $actual = ESStore::fold($base)->dropLast(2)->value();
        $this->assertSame($expected, $actual);
    }

    public function testArray()
    {
        $base = "/root/data/inner-folder";
        $expected = ["root", "data", "inner-folder"];
        $actual = ESStore::fold($base)->array();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testParent()
    {
        $base = __DIR__ ."/data/inner-folder/subfolder/inner.md";
        $expected = __DIR__ ."/data/inner-folder/subfolder";
        $actual = ESStore::fold($base)->parent();
        $this->assertSame($expected, $actual->unfold());

        $base = __DIR__ ."/data/inner-folder/subfolder";
        $expected = __DIR__ ."/data/inner-folder";
        $actual = ESStore::fold($base)->parent();
        $this->assertSame($expected, $actual->unfold());
    }

    public function testCanUnfold()
    {
        $expected = Shoop::string(__DIR__)->divide("/")->dropLast()
            ->plus("src", "Routes", "any.php")->join("/");
        $actual = Shoop::store(__DIR__)->dropLast()
            ->plus("src", "Routes", "any.php");
        $this->assertSame($expected->unfold(), $actual->unfold());
    }
}

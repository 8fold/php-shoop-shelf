<?php

namespace Eightfold\ShoopExtras\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\ShoopExtras\ESStore;

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

        $expected = [__DIR__ ."/data/inner-folder"];
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
}

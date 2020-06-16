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
}

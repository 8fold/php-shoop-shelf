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

        $actual = ESStore::fold($path)->isFolder(function($result) {
            return $result->unfold();
        });
        $this->assertTrue($actual);

        $path = $path ."/content.md";
        $actual = ESStore::fold($path)->isFile;
        $this->assertTrue($actual);

        $actual = ESStore::fold($path)->isFile(function($result, $p) {
            $this->assertTrue(is_a($p, ESStore::class));
            return $result->unfold();
        });
        $this->assertTrue($actual);
    }

    public function testCanGetContents()
    {
        $path = __DIR__ ."/data";

        $expected = [
            __DIR__ ."/data/inner-folder/content.md",
            __DIR__ ."/data/inner-folder/file.extension",
            __DIR__ ."/data/inner-folder/subfolder"
        ];
        $actual = ESStore::fold($path)->plus("inner-folder")->content();
        $this->assertSame($expected, $actual->unfold());

        $expected = [
            __DIR__ ."/data/inner-folder/subfolder"
        ];
        $actual = ESStore::fold($path)->plus("inner-folder")->folders;
        $this->assertSame($expected, $actual);

        $expected = [
            __DIR__ ."/data/inner-folder/content.md",
            __DIR__ ."/data/inner-folder/file.extension"
        ];
        $actual = ESStore::fold($path)->plus("inner-folder")->files;
        $this->assertSame($expected, $actual);

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
        $expected = [
            __DIR__ ."/data/inner-folder/content.md",
            __DIR__ ."/data/inner-folder/file.extension"
        ];
        $actual = ESStore::fold($path)->files;
        $this->assertSame($expected, $actual);

        $path = __DIR__ ."/data/inner-folder";
        $expected = [__DIR__ ."/data/inner-folder/file.extension"];
        $actual = ESStore::fold($path)->files(true, [], "extension");
        $this->assertSame($expected, $actual->unfold());
    }

    public function testCanGetMetaMember()
    {
        $path = __DIR__ ."/data/inner-folder/subfolder/inner.md";
        $expected = "Something";
        $actual = ESStore::fold($path)->metaMember("title");
        $this->assertSame($expected, $actual->unfold());

        $expected = "";
        $actual = ESStore::fold($path)->metaMember("heading");
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testCanUnfold()
    {
        $expected = Shoop::string(__DIR__)->divide("/")->dropLast()
            ->plus("src", "Routes", "any.php")->join("/");
        $actual = Shoop::store(__DIR__)->dropLast()
            ->plus("src", "Routes", "any.php");
        $this->assertSame($expected->unfold(), $actual->unfold());
    }

    public function testParent()
    {
        $expected = Shoop::string(__DIR__)->divide("/")->dropLast();
        $actual = Shoop::store(__DIR__)->dropLast();
        $this->assertTrue(is_a($actual, ESStore::class));

        $actual = $actual->plus("src", "Routes", "any.php");
        $this->assertTrue(is_a($actual, ESStore::class));

        $actual = $actual->dropLast(3);
        $this->assertSame($expected, $actual->unfold());
    }

    public function testCanCheckEndsWith()
    {
        $expected = true;
        $actual = Shoop::store(__DIR__)->plus("file.extension")
            ->endsWith(".extension");
        $this->assertSame($expected, $actual->unfold());
    }
}

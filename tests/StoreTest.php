<?php

namespace Eightfold\ShoopShelf\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\ShoopShelf\Shoop;

/**
 * @group Store
 */
class StoreTest extends TestCase
{
    public function tearDown(): void
    {
        Shoop::store(__DIR__)->plus("data", ".writing")->delete();
    }

    /**
     * @test
     */
    public function check_is_file_or_folder()
    {
        $path = __DIR__ ."/data/inner-folder/subfolder/inner.md";

        AssertEquals::applyWith(
            true,
            "boolean",
            4.62 // 3.62 // 3.13 // 2.28 // 2.21
        )->unfoldUsing(
            Shoop::store(__DIR__)->plus(
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            )->isFile()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            0.63 // 0.6
        )->unfoldUsing(
            Shoop::store(__DIR__)->plus(
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            )->isFolder()
        );
    }

    /**
     * @test
     */
    public function can_get_contents_based_on_folder_or_file()
    {
        $path = __DIR__ ."/data";

        $expected = [__DIR__ ."/data/inner-folder/subfolder"];
        AssertEquals::applyWith(
            $expected,
            "array",
            7.49, // 3.6
            19
        )->unfoldUsing(
            Shoop::store($path)->plus("inner-folder")->folders()
        );

        $expected = [
            __DIR__ ."/data/inner-folder/content.md",
            __DIR__ ."/data/inner-folder/file.extension"
        ];
        AssertEquals::applyWith(
            $expected,
            "array"
        )->unfoldUsing(
            Shoop::store($path)->plus("inner-folder")->files()
        );

        $expected = [
            __DIR__ ."/data/inner-folder",
            __DIR__ ."/data/link.md",
            __DIR__ ."/data/table.md",
        ];
        AssertEquals::applyWith(
            $expected,
            "array"
        )->unfoldUsing(
            Shoop::store($path)->content()
        );

        $expected = [
            __DIR__ ."/data/.",
            __DIR__ ."/data/..",
            __DIR__ ."/data/.writing",
            __DIR__ ."/data/inner-folder",
            __DIR__ ."/data/link.md",
            __DIR__ ."/data/table.md",
        ];
        $expected = [
            __DIR__ ."/data/inner-folder",
            __DIR__ ."/data/link.md",
            __DIR__ ."/data/table.md",
        ];
        AssertEquals::applyWith(
            $expected,
            "array"
        )->unfoldUsing(
            Shoop::store($path)->content()
        );

        $path = $path ."/inner-folder/content.md";
        AssertEquals::applyWith(
            "Hello, World!\n",
            "string",
            1.55 // 0.86
        )->unfoldUsing(
            Shoop::store($path)->content(true, false)
        );
    }

    /**
     * @test
     *
     * Create a file with string content in the destination folder.
     *
     * Automatically:
     * - recursive,
     * - mode of 0755, and
     * - forced.
     */
    public function writing_to_path()
    {
        $base = __DIR__;
        $store = Shoop::store($base)->plus("data", ".writing", "first");

        AssertEquals::applyWith(
            "Hello, World!",
            "string",
            3.71, // 2.6 // 2.53 // 2.31 // 1.98 // 1.09
            30
        )->unfoldUsing(
            Shoop::store(__DIR__)->plus("data", ".writing", "first")
                ->saveContent("Hello, World!")->content()
        );
    }

    /**
     * @test
     */
    public function parentage()
    {
        $expected = Shoop::string(__DIR__)->divide("/")->minusLast()->join("/");

        AssertEquals::applyWith(
            $expected,
            "string",
            0.92 // 0.43 // 0.36
        )->unfoldUsing(
            Shoop::store(__DIR__)->minusLast()
        );
    }
}

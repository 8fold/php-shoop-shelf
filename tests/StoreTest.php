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
        Shoop::store(__DIR__)->append(["data", ".writing"])->delete();
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
            7.28, // 5.89, // 4.13, // 4.07, // 4, // 3.94, // 3.85, // 3.44,
            346 // 345 // 334 // 333 // 332 // 331 // 330
        )->unfoldUsing(
            Shoop::store(__DIR__)->append([
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            ])->isFile()
        );

        AssertEquals::applyWith(
            false,
            "boolean",
            0.22, // 0.21, // 0.08, // 0.07,
            1
        )->unfoldUsing(
            Shoop::store(__DIR__)->append([
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            ])->isFolder()
        );
    }

    /**
     * @test
     */
    public function can_get_contents_based_on_folder_or_file()
    {
        $path = __DIR__ ."/data";

        // $expected = [__DIR__ ."/data/inner-folder/subfolder"];
        // AssertEquals::applyWith(
        //     $expected,
        //     "array",
        //     8.42, // 7.26, // 6.67, // 5.82, // 4.93, // 4.89, // 4.22,
        //     758 // 750 // 381 // 380 // 379 // 373
        // )->unfoldUsing(
        //     Shoop::store($path)->append(["inner-folder"])->folders()
        // );

        // AssertEquals::applyWith(
        //     "Hello, World!\n",
        //     "string",
        //     6.1, // 2.42,
        //     715 // 306
        // )->unfoldUsing(
        //     Shoop::store($path)->append(["inner-folder", "content.md"])->content()
        // );

        // $expected = [
        //     __DIR__ ."/data/inner-folder/content.md",
        //     __DIR__ ."/data/inner-folder/file.extension"
        // ];
        // AssertEquals::applyWith(
        //     $expected,
        //     "array",
        //     7.09, // 6.39, // 0.42,
        //     758
        // )->unfoldUsing(
        //     Shoop::store($path)->append(["inner-folder"])->files()
        // );

        $expected = [
            __DIR__ ."/data/inner-folder",
            __DIR__ ."/data/link.md",
            __DIR__ ."/data/table.md",
        ];

        AssertEquals::applyWith(
            $expected,
            "array",
            6.82, // 6.73, // 0.38, // 0.29, // 0.28,
            756
        )->unfoldUsing(
            Shoop::store($path)->content()
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
        AssertEquals::applyWith(
            "Hello, World!",
            "string",
            9.79, // 7.4, // 7.13, // 6.87, // 6.65, // 5.26, // 5.25,
            716 // 373 // 372
        )->unfoldUsing(
            Shoop::store(__DIR__)->append(["data", ".writing", "first"])
                ->saveContent("Hello, World!")->content()
        );
    }

    /**
     * @test
     */
    public function parentage()
    {
        $expected = Shoop::this(__DIR__)->divide("/")
            ->dropLast()->efToString("/");

        AssertEquals::applyWith(
            $expected,
            "string",
            0.37, // 0.35,
            22 // 20
        )->unfoldUsing(
            Shoop::store(__DIR__)->up()
        );
    }
}

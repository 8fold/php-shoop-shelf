<?php

namespace Eightfold\ShoopShelf\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\ShoopShelf\FluentTypes\ESPath;
use Eightfold\ShoopShelf\FluentTypes\ESScheme;

/**
 * @group Uri
 */
class UriTest extends TestCase
{
    /**
     * @test
     */
    public function scheme_and_path()
    {
        $uri = "mailto:admin@8fold.link";

        AssertEquals::applyWith(
            "mailto",
            "string",
            3.28 // 3.16 // 2.45 // 2.39
        )->unfoldUsing(
            Shoop::uri($uri)->scheme()
        );

        AssertEquals::applyWith(
            "admin@8fold.link",
            "string",
            3.18
        )->unfoldUsing(
            Shoop::uri($uri)->path()
        );

        AssertEquals::applyWith(
            "/",
            "string",
            3.55
        )->unfoldUsing(
            Shoop::uri("https://authority/")->path()
        );
    }

    /**
     * @test
     * @group current
     */
    public function query_and_fragment()
    {
        $url = "https://admin:password@8fold.link:8888/some/path/to?post=12#fragment";

        AssertEquals::applyWith(
            "https",
            "string",
            3.33 // 2.9
        )->unfoldUsing(
            Shoop::uri($url)->scheme()
        );

        AssertEquals::applyWith(
            "admin:password@8fold.link:8888",
            "string"
        )->unfoldUsing(
            Shoop::uri($url)->authority()
        );

        AssertEquals::applyWith(
            "admin",
            "string"
        )->unfoldUsing(
            Shoop::uri($url)->username()
        );

        AssertEquals::applyWith(
            "password",
            "string"
        )->unfoldUsing(
            Shoop::uri($url)->password()
        );

        AssertEquals::applyWith(
            "8fold.link",
            "string"
        )->unfoldUsing(
            Shoop::uri($url)->host()
        );

        AssertEquals::applyWith(
            "8888",
            "string"
        )->unfoldUsing(
            Shoop::uri($url)->port()
        );

        AssertEquals::applyWith(
            "/some/path/to",
            "string",
            4.41
        )->unfoldUsing(
            Shoop::uri($url)->path()
        );

        AssertEquals::applyWith(
            ["post" => "12"],
            "array",
            1.11
        )->unfoldUsing(
            Shoop::uri($url)->query()
        );

        AssertEquals::applyWith(
            "fragment",
            "string"
        )->unfoldUsing(
            Shoop::uri($url)->fragment()
        );
    }
}

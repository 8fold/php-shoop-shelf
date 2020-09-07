<?php

namespace Eightfold\ShoopShelf\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\ShoopShelf\Shoop;

/**
 * @group Url
 */
class UrlTest extends TestCase
{
    /**
     * @test
     */
    public function url_parts()
    {
        $url = "https://admin:password@8fold.link:8888/some/path/to?post=12#fragment";

        $actual = Shoop::url($url);

        AssertEquals::applyWith(
            "https",
            "string",
            3.33 // 3.24 // 3.16 // 2.62 // 2.5
        )->unfoldUsing(
            Shoop::url($url)->scheme()
        );

        AssertEquals::applyWith(
            "/some/path/to",
            "string"
        )->unfoldUsing(
            Shoop::url($url)->path(false)
        );

        AssertEquals::applyWith(
            "admin",
            "string"
        )->unfoldUsing(
            Shoop::url($url)->userInfo()
        );

        // $expected = "admin";
        // $this->assertSame($expected, $actual->user()->unfold());

        // $expected = "password";
        // $this->assertSame($expected, $actual->password()->unfold());

        // $expected = "8fold.link";
        // $this->assertSame($expected, $actual->host()->unfold());

        // $expected = "8888";
        // $this->assertSame($expected, $actual->port()->unfold());

        // $expected = ["post" => "12"];
        // $this->assertSame($expected, $actual->query()->unfold());

        // $expected = "fragment";
        // $this->assertSame($expected, $actual->fragment()->unfold());
    }
}

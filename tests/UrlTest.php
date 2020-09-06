<?php

namespace Eightfold\ShoopShelf\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\ESUrl;

class UrlTest extends TestCase
{
    public function testParts()
    {
        $url = "https://admin:password@8fold.link:8888/some/path/to?post=12#fragment";

        $actual = ESUrl::fold($url);

        $expected = $url;
        $a = $actual->main();
        $this->assertSame($expected, $a);

        $expected = "https";
        $this->assertSame($expected, $actual->scheme()->unfold());

        // TODO: Prefix with a forward slash - BC
        $expected = "/some/path/to";
        $a = $actual->path(false);
        $this->assertSame($expected, $a->unfold());

        $expected = "admin:password@8fold.link:8888/some/path/to?post=12#fragment";
        $this->assertSame($expected, $actual->path()->unfold());

        $expected = "admin";
        $this->assertSame($expected, $actual->user()->unfold());

        $expected = "password";
        $this->assertSame($expected, $actual->password()->unfold());

        $expected = "8fold.link";
        $this->assertSame($expected, $actual->host()->unfold());

        $expected = "8888";
        $this->assertSame($expected, $actual->port()->unfold());

        $expected = ["post" => "12"];
        $this->assertSame($expected, $actual->query()->unfold());

        $expected = "fragment";
        $this->assertSame($expected, $actual->fragment()->unfold());
    }
}

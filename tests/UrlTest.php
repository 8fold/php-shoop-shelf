<?php

namespace Eightfold\ShoopExtras\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\ShoopExtras\{
    Shoop,
    ESUrl
};

class UrlTest extends TestCase
{
    public function testParts()
    {
        $url = "https://admin:password@8fold.link:8888/some/path/to?post=12#fragment";

        $actual = ESUrl::fold($url);

        $expected = $url;
        $a = $actual->value();
        $this->assertSame($expected, $a->unfold());

        $expected = "https";
        $this->assertSame($expected, $actual->scheme()->unfold());

        $expected = "some/path/to";
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

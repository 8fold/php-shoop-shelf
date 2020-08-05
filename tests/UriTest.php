<?php

namespace Eightfold\ShoopExtras\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\ShoopExtras\{
    Shoop,
    ESUri
};

class UriTest extends TestCase
{
    public function testParts()
    {
        $uri = "mailto:admin@8fold.link";

        $actual = ESUri::fold($uri);

        $expected = $uri;
        $a = $actual->value();
        $this->assertSame($expected, $a->unfold());

        $expected = "mailto";
        $this->assertSame($expected, $actual->scheme);

        $expected = "admin@8fold.link";
        $a = $actual->path(false);
        $this->assertSame($expected, $a->unfold());

        $expected = "admin@8fold.link";
        $this->assertSame($expected, $actual->path);
    }
}

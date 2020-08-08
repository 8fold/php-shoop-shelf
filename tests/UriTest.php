<?php

namespace Eightfold\ShoopExtras\Tests;

use PHPUnit\Framework\TestCase;

use Eightfold\ShoopExtras\{
    Shoop,
    ESPath,
    ESUri
};

class UriTest extends TestCase
{
    public function testParts()
    {
        $uri = "mailto:admin@8fold.link";

        $actual = ESUri::fold($uri);

        $expected = $uri;
        $a = $actual;
        $this->assertSame($expected, $a->unfold());

        // TODO: Make more shoop-like
        $expected = "mailto";
        $this->assertSame($expected, $actual->scheme()->unfold());

        $expected = "admin@8fold.link";
        $a = $actual->path(false);
        $this->assertSame($expected, $a->unfold());

        // TODO: Make more shoop-like
        $expected = "admin@8fold.link";
        $this->assertSame($expected, $actual->path()->unfold());

        $this->assertTrue(is_a($actual->path(), ESPath::class));
    }
}

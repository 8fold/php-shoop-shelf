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
    public function testParts()
    {
        $uri = "mailto:admin@8fold.link";

        AssertEquals::applyWith(
            "mailto",
            "string",
            3.52 // 3.37 // 3.3 // 3.24 // 2.64
        )->unfoldUsing(
            Shoop::uri($uri)->scheme()
        );

        AssertEquals::applyWith(
            "admin@8fold.link",
            "string",
            1.34 // 1.1
        )->unfoldUsing(
            Shoop::uri($uri)->path()
        );

        $this->assertTrue(is_a(Shoop::uri($uri)->scheme(), ESScheme::class));
        $this->assertTrue(is_a(Shoop::uri($uri)->path(), ESPath::class));
    }
}

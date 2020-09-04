<?php

namespace Eightfold\Shoop\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Shoop\Tests\AssertEqualsFluent;

class BoolTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        $this->assertTrue(true);
    }

    /**
     * @return Eightfold\Shoop\ESBoolean Sets the value of the bool to the given bool.
     */
    public function ESBoolean()
    {
        $base = true;
        $actual = ESBoolean::fold($base)->set(false);
        $this->assertFalse($actual->unfold());
    }

    public function ESBoolean()
    {
        $expected = '{"true":true,"false":false}';
        $actual = json_encode(Shoop::bool(true));
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function ESBoolean()
    {
        $compare = false;
        AssertEqualsFluent::applyWith(
            false,
            ESBoolean::class,
            5.55
        )->unfoldUsing(
            Shoop::this(true)->isLessThan($compare)
        );

        $compare = true;
        AssertEqualsFluent::applyWith(
            true,
            ESBoolean::class
        )->unfoldUsing(
            Shoop::this(false)->isLessThan($compare)
        );
    }

    /**
     * @test
     */
    public function ESBoolean()
    {
        AssertEqualsFluent::applyWith(true, 1.26)
            ->unfoldUsing(ESBoolean::fold(true)->asBoolean());

        AssertEqualsFluent::applyWith(false)
            ->unfoldUsing(ESBoolean::fold(false)->asBoolean());
    }

    /**
     * @test
     */
    public function ESBoolean()
    {
        AssertEqualsFluent::applyWith(["true" => true, "false" => false], 1.8)
            ->unfoldUsing(ESBoolean::fold(true)->asDictionary());
    }

    /**
     * @test
     */
    public function ESBoolean()
    {
        AssertEqualsFluent::applyWith(1, 1.92)
            ->unfoldUsing(ESBoolean::fold(true)->asInteger());

        AssertEqualsFluent::applyWith(0)
            ->unfoldUsing(ESBoolean::fold(false)->asInteger());
    }

    /**
     * @test
     */
    public function ESBoolean()
    {
        AssertEqualsFluent::applyWith('{"false":false,"true":true}', 2)
            ->unfoldUsing(ESBoolean::fold(true)->asJson());
    }

    /**
     * @test
     */
    public function ESBoolean()
    {
        AssertEqualsFluent::applyWith("true", 2.87)
            ->unfoldUsing(ESBoolean::fold(true)->asString());
    }

    /**
     * @test
     */
    public function ESBoolean()
    {
        AssertEqualsFluent::applyWith(
            (object) ["false" => false, "true" => true], 4.14
        )->unfoldUsing(ESBoolean::fold(true)->asTuple());

        AssertEqualsFluent::applyWith(
            (object) ["false" => true, "true" => false]
        )->unfoldUsing(ESBoolean::fold(false)->asTuple());
    }
}

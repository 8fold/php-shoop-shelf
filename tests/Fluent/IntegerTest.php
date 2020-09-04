<?php

namespace Eightfold\Shoop\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Shoop\Tests\AssertEqualsFluent;

class IntegerTest extends TestCase
{
    /**
     * @test
     */
    public function divide()
    {
        $expected = 2;
        $actual = ESInteger::fold(5)->divide(3);
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @return Eightfold\Shoop\Int Where the original value is multiplied by the given integer.
     */
    public function ESInteger()
    {
        $expected = 15;
        $actual = ESInteger::fold(5)->multiply(3);
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @return Eightfold\Shoop\ESINt Sets the value of the integer to the given integer.
     */
    public function ESInteger()
    {
        $base = 10;

        $expected = 12;
        $actual = ESInteger::fold($base)->set(12);
        $this->assertEquals($expected, $actual->unfold());
    }

    public function ESInteger()
    {
        $expected = '{"i0":0,"i1":1}';
        $actual = json_encode(Shoop::integer(1));
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function ESInteger()
    {
        AssertEqualsFluent::applyWith(true, 1.59)
            ->unfoldUsing(ESInteger::fold(1)->asBoolean());

        AssertEqualsFluent::applyWith(false)
            ->unfoldUsing(ESInteger::fold(0)->asBoolean());
    }

    /**
     * @test
     */
    public function ESInteger()
    {
        AssertEqualsFluent::applyWith(["i0" => 0, "i1" => 1, "i2" => 2], 1.74)
            ->unfoldUsing(ESInteger::fold(2)->asDictionary());
    }

    /**
     * @test
     */
    public function ESInteger()
    {
        AssertEqualsFluent::applyWith(1, 1.51)
            ->unfoldUsing(ESInteger::fold(1)->asInteger());
    }

    /**
     * @test
     */
    public function ESInteger()
    {
        AssertEqualsFluent::applyWith('{"i0":0,"i1":1}', 3.29)
            ->unfoldUsing(ESInteger::fold(1)->asJson());
    }

    /**
     * @test
     */
    public function ESInteger()
    {
        AssertEqualsFluent::applyWith(
            "1",
            ESString::class,
            2.56
        )->unfoldUsing(
            Shoop::this(1)->asString()
        );
    }

    /**
     * @test
     */
    public function ESInteger()
    {
        AssertEqualsFluent::applyWith((object) ["i0" => 0, "i1" => 1], 3.86)
            ->unfoldUsing(ESInteger::fold(1)->asTuple());
    }
}

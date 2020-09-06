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

        $base = 10;

        $expected = 12;
        $actual = ESInteger::fold($base)->set(12);
        $this->assertEquals($expected, $actual->unfold());

        $expected = '{"i0":0,"i1":1}';
        $actual = json_encode(Shoop::integer(1));
        $this->assertEquals($expected, $actual);

        AssertEqualsFluent::applyWith(true, 1.59)
            ->unfoldUsing(ESInteger::fold(1)->asBoolean());

        AssertEqualsFluent::applyWith(false)
            ->unfoldUsing(ESInteger::fold(0)->asBoolean());

        AssertEqualsFluent::applyWith(["i0" => 0, "i1" => 1, "i2" => 2], 1.74)
            ->unfoldUsing(ESInteger::fold(2)->asDictionary());

        AssertEqualsFluent::applyWith(1, 1.51)
            ->unfoldUsing(ESInteger::fold(1)->asInteger());

        AssertEqualsFluent::applyWith('{"i0":0,"i1":1}', 3.29)
            ->unfoldUsing(ESInteger::fold(1)->asJson());

        AssertEqualsFluent::applyWith(
            "1",
            ESString::class,
            2.56
        )->unfoldUsing(
            Shoop::this(1)->asString()
        );

        AssertEqualsFluent::applyWith((object) ["i0" => 0, "i1" => 1], 3.86)
            ->unfoldUsing(ESInteger::fold(1)->asTuple());
   }
}

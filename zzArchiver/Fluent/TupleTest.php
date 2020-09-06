<?php

namespace Eightfold\Shoop\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Shoop\Tests\AssertEqualsFluent;

class TupleTest extends TestCase
{
    /**
     * @test
     */
    public function divide()
    {
        $expected = new \stdClass();
        $expected->members = ["member", "member2"];
        $expected->values = ["value", "value2"];

        $base = new \stdClass();
        $base->member = "value";
        $base->member2 = "value2";
        $actual = ESTuple::fold($base)->divide();
        $this->assertEquals($expected, $actual->unfold());

        $base->member3 = "";
        $actual = ESTuple::fold($base)->divide(0, false);
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @test
     */
    public function ESTuple()
    {
        $base = new \stdClass();
        $base->testMember = "test";

        $expected = new \stdClass();
        $actual = ESTuple::fold($base)->dropFirst();
        $this->assertEquals($expected, $actual->unfold());

        $base = new \stdClass();
        $base->testMember = "test";

        $expected = new \stdClass();
        $actual = ESTuple::fold($base)->dropLast();
        $this->assertEquals($expected, $actual->unfold());

        $base = new \stdClass();
        $base->testMember = "test";

        $expected = new \stdClass();
        $actual = ESTuple::fold($base)->drop("testMember");
        $this->assertEquals($expected, $actual->unfold());

        $base = new \stdClass();
        $base->testMember = "test";

        $expected = new \stdClass();
        $expected->testMember = "test";

        $actual = ESTuple::fold($base)->noEmpties();
        $this->assertEquals($expected, $actual->unfold());

        $object = new \stdClass();
        $object->members = ["member", "member2"];
        $object->values = ["value", "value2"];

        $expected = [$object];
        $actual = ESTuple::fold($object)->multiply();
        $this->assertEquals($expected, $actual->unfold());

        $expected = new \stdClass();
        $expected->test = "test";
        $actual = ESTuple::fold(new \stdClass())->set("test", "test");
        $this->assertEquals($expected, $actual->unfold());

        $base = new \stdClass();
        $base->testMember = "test";

        $actual = ESTuple::fold($base)->hasMember("testMember");
        $this->assertTrue($actual->unfold());

        $actual = ESTuple::fold($base)->hasMember(
            "testMember", function($result, $value) {
            return $result;
        });
        $this->assertTrue($actual->unfold());

        $actual = ESTuple::fold($base)->doesNotHaveMember(
            "array", function($result, $value) {
            return $result;
        });
        $this->assertTrue($actual->unfold());

        $expected = "{}";
        $actual = json_encode(Shoop::object(new \stdClass()));
        $this->assertEquals($expected, $actual);

        $compare = (object) ["a" => 2, "b" => 3, "c" => 4];
        AssertEqualsFluent::applyWith(
            true,
            ESBoolean::class,
            2.77
        )->unfoldUsing(
            Shoop::this((object) ["a" => 2, "b" => 3, "c" => 4])
                ->isLessThanOrEqualTo($compare)
        );

        $using = new \stdClass();

        AssertEqualsFluent::applyWith(false, 1.37)
            ->unfoldUsing(ESTuple::fold($using)->asBoolean());

        $using->name = "hello";

        AssertEqualsFluent::applyWith(true)
            ->unfoldUsing(ESTuple::fold($using)->asBoolean());

        AssertEqualsFluent::applyWith(["test" => true], 1.67)
            ->unfoldUsing(ESTuple::fold((object) ["test" => true])->asDictionary());

        AssertEqualsFluent::applyWith(0, 3.34)
            ->unfoldUsing(ESTuple::fold(new stdClass)->asInteger());

        AssertEqualsFluent::applyWith('{}', 11.35)
            ->unfoldUsing(ESTuple::fold(new stdClass)->asJson());

        AssertEqualsFluent::applyWith(
            "",
            ESString::class
        )->unfoldUsing(
            Shoop::this(new stdClass)->asString()
        );

        AssertEqualsFluent::applyWith(new stdClass, 3.18)
            ->unfoldUsing(ESTuple::fold(new stdClass)->asTuple());

        $base = new \stdClass();
        $base->testMember = "test";

        $actual = ESTuple::fold($base)->has("test");
        $this->assertTrue($actual->unfold());

        $expected = "t";
        $actual = ESTuple::fold($base)->has("test", function($result, $value) {
            if ($result->unfold()) {
                return "t";
            }
            return null;
        });
        $this->assertSame($expected, $actual->unfold());

        $expected = "t";
        $actual = ESTuple::fold($base)->doesNothave(
            "hi", function($result, $value) {
                if ($result->unfold()) {
                    return null;
                }
                return "t";
        });
        $this->assertSame($expected, $actual->unfold());

        $actual = ESTuple::fold($base)->hasMember("testMember");
        $this->assertTrue($actual->unfold());

        $base = new \stdClass();
        $base->testMember = "test";
        $base->testMember2 = 2;

        $actual = Shoop::object($base)->doesNotEndWith("test", "testMember");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());

        $base = new \stdClass();
        $base->testMember = "test";
        $base->testMember2 = 2;

        $actual = Shoop::object($base)->doesNotStartWith("test", "testMember");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertFalse($actual->unfold());

        $base = new \stdClass();
        $base->testMember = "test";
        $base->testMember2 = 2;

        $actual = Shoop::object($base)->endsWith("test", "testMember");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertFalse($actual->unfold());

        $actual = ESTuple::fold($base)->endsWith(
            2, "testMember2",
            function($result, $value) {
                if ($result) {
                    return $value;
                }
                return false;
        });
        $this->assertSame($base, $actual->unfold());

        $base = new \stdClass();
        $base->testMember = new \stdClass();
        $base->testMember2 = 2;

        $expected = $base;
        $actual = Shoop::object(new \stdClass())->end(new \stdClass(), "testMember", 2, "testMember2");
        $this->assertEquals(ESTuple::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());

        $base = new \stdClass();
        $base->testMember = new \stdClass();

        $expected = new \stdClass();
        $actual = ESTuple::fold($base)->first();
        $this->assertEquals(ESTuple::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());

        $base = new \stdClass();
        $base->testMember = new \stdClass();
        $base->testMember2 = 2;

        $expected = 2;
        $actual = ESTuple::fold($base)->last();
        $this->assertEquals(ESInteger::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());

        $expected = [new \stdClass(), 2];
        $actual = ESTuple::fold($base)->last(2);
        $this->assertEquals($expected, $actual->unfold());

        $base = new \stdClass();
        $base->testMember = "test";
        $base->testMember2 = 2;

        $actual = Shoop::object($base)->startsWith("test", "testMember");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());

        $actual = ESTuple::fold($base)->startsWith(
            "test", "testMember", function($result, $value) {
                if ($result->unfold()) {
                    return $value;
                }
                return false;
        });
        $this->assertSame($base, $actual->unfold());

        $base = new \stdClass();
        $base->testMember = new \stdClass();
        $base->testMember2 = 2;

        $expected = $base;
        $actual = Shoop::object(new \stdClass())->start(new \stdClass(), "testMember", 2, "testMember2");
        $this->assertEquals(ESTuple::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @test
     */
    public function ESArray()
    {
        $expected = [
            ["goodbye", "hello"],
            ["goodbye", "hello"],
            ["goodbye", "hello"]
        ];
        $actual = ESArray::fold(["goodbye", "hello"])->multiply(3);
        $this->assertEquals($expected, $actual->unfold());
    }

    public function ESString()
    {
        $expected = "Hello, World!";
        $actual = Shoop::string("World!")->start("Hello, ");
        $this->assertEquals(ESString::class, get_class($actual));
        $this->assertEquals($expected, $actual);
    }
}

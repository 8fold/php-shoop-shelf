<?php

namespace Eightfold\Shoop\FluentTypes\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Shoop\Tests\AssertEqualsFluent;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\FluentTypes\ESArray;
use Eightfold\Shoop\FluentTypes\ESString;

/**
 * @group ArrayFluent
 */
class ArrayTest extends TestCase
{
    /**
     * @test
     *
     * The `join()` method on ESArray is similar to the `imploded()` function from the PHP standard library.
     *
     * @return Eightfold\Shoop\FluentTypes\ESString
     */
    public function join()
    {
        AssertEqualsFluent::applyWith(
             "Hello, World!",
             ESString::class,
             12.48
         )->unfoldUsing(
            Shoop::this(["Hello", "World!"])->asString(", ")
        );
    }

    /**
     * @test
     */
    public function title_builder()
    {
        AssertEqualsFluent::applyWith(
             "First | Second | Third",
             ESString::class,
             3.63
         )->unfoldUsing(
            Shoop::this(["Second", "Third"])
                ->prepend("First")->asString(" | ")
        );
    }

    /**
     * @test
     */
    public function random()
    {
        AssertEqualsFluent::applyWith(
             "hello",
             ESString::class,
             5.49
         )->unfoldUsing(
            Shoop::this(["hello"])->random()
        );

        AssertEqualsFluent::applyWith(
             ["hello", "hello"],
             ESArray::class
         )->unfoldUsing(
            Shoop::this(["hello", "hello", "hello"])->random(2)
        );
    }

    /**
     * @test
     */
    public function divide()
    {
        $expected = [
            ["hello"],
            ["goodbye", "hello"]
        ];
        $actual = ESArray::fold(["hello", "goodbye", "hello"])->divide(1);
        $this->assertEquals($expected, $actual->unfold());

        $actual = ESArray::fold(["hello", "", null, "goodbye", "", "hello"])
            ->divide(2, false);
        $this->assertSame($expected, $actual->unfold());
    }

    /**
     * @test
     */
    public function ESArray()
    {
        $using = ["hello", "goodbye", "hello"];
        AssertEqualsFluent::applyWith(
            ["goodbye"],
            ESArray::class,
            3.63
        )->unfoldUsing(
            Shoop::this($using)->minusAt([0, 2])
        );

        $base = ["hello", "world"];

        $expected = [];
        $actual = Shoop::array($base)->dropFirst(2);
        $this->assertEquals($expected, $actual->unfold());

        $base = ["hello", "world"];

        $expected = [];
        $actual = Shoop::array($base)->dropLast(2);
        $this->assertEquals($expected, $actual->unfold());

        $base = ["hello", "world"];

        $expected = ["hello"];
        $actual = Shoop::array($base)->drop(1);
        $this->assertEquals($expected, $actual->unfold());

        $base = [0, null];

        $expected = [];
        $actual = Shoop::array($base)->noEmpties();
        $this->assertEquals($expected, $actual->unfold());

        $expected = ["hello", "world"];
        $actual = ESArray::fold(["hello", "Shoop"])->set("world", 1);
        $this->assertEquals($expected, $actual->unfold());

        $expected = "{}";
        $actual = json_encode(ESArray::fold([]));

        $this->assertEquals($expected, $actual);

        AssertEqualsFluent::applyWith(
            [],
            ESArray::class,
            1.7
        )->unfoldUsing(
            Shoop::this([])->asArray()
        );

        $base = ["hello", "world"];
        $actual = ESArray::fold($base)->has("world");
        $this->assertTrue($actual->unfold());

        $expected = "h";
        $actual = ESArray::fold($base)->has("hello", function($result, $value) {
            if ($result->unfold()) {
                return "h";
            }
            return null;
        });
        $this->assertSame($expected, $actual->unfold());

        $expected = "h";
        $actual = ESArray::fold($base)->doesNothave(
            "hi", function($result, $value) {
                if ($result->unfold()) {
                    return null;
                }
                return "h";
        });
        $this->assertSame($expected, $actual->unfold());

        $actual = ESArray::fold($base)->hasMember(0);
        $this->assertTrue($actual->unfold());

        $base = ["hello", "world"];
        $actual = ESArray::fold($base)->hasMember(1);
        $this->assertTrue($actual->unfold());

        $actual = ESArray::fold($base)->hasMember(0, function($result, $value) {
            return $result;
        });
        $this->assertTrue($actual->unfold());

        $actual = ESArray::fold($base)->doesNotHaveMember(
            0, function($result, $value) {
            return $result;
        });
        $this->assertFalse($actual->unfold());

        AssertEqualsFluent::applyWith(true, 6.43)
            ->unfoldUsing(ESArray::fold(["testing"])->asBoolean());

        AssertEqualsFluent::applyWith(false)
            ->unfoldUsing(
                ESArray::fold([])->asBoolean()
            );

        AssertEqualsFluent::applyWith(["i0" => "hi"], 1.64)
            ->unfoldUsing(ESArray::fold(["hi"])->asDictionary());

        AssertEqualsFluent::applyWith(1, 1.58)
            ->unfoldUsing(ESArray::fold(['testing'])->asInteger());

        AssertEqualsFluent::applyWith('{}', 3.67)
            ->unfoldUsing(ESArray::fold([])->asJson());

        AssertEqualsFluent::applyWith('{"i0":"testing"}', 2.93)
            ->unfoldUsing(ESArray::fold(['testing'])->asJson());

        AssertEqualsFluent::applyWith(
            "",
            ESString::class,
            9.19
        )->unfoldUsing(
            Shoop::this([])->asString()
        );

        AssertEqualsFluent::applyWith(
            "testing something",
            ESString::class
        )->unfoldUsing(
            Shoop::this(["testing", " something"])->asString()
        );

        AssertEqualsFluent::applyWith((object) ["i0" => "testing"], 5.08)
            ->unfoldUsing(ESArray::fold(["testing"])->asTuple());

        $base = ["something", "hello", "world"];

        $actual = Shoop::array($base)->doesNotEndWith("something");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());

        $base = ["something", "hello", "world"];

        $actual = Shoop::array($base)->doesNotStartWith("hello", "world");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());

        $base = ["something", "hello", "world"];

        $actual = Shoop::array($base)->endsWith("hello", "world");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());

        $actual = Shoop::array($base)->endsWith(
            "hello", "world", function($result, $value) {
                if ($result) {
                    return $value;
                }
                return false;
        });
        $this->assertSame($base, $actual->unfold());

        $base = ["hello", "world"];

        $expected = ["hello", "world", "something"];
        $actual = Shoop::array($base)->end("something");
        $this->assertEquals(ESArray::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());

        $base = ["hello", "world"];

        $expected = "hello";
        $actual = Shoop::array($base)->first();
        $this->assertEquals(ESString::class, get_class($actual));
        $this->assertEquals($expected, $actual);

        $expected = ["hello", "world"];
        $actual = Shoop::array($base)->first(2);
        $this->assertEquals($expected, $actual->unfold());

        $base = ["hello", "world"];

        $expected = "world";
        $actual = Shoop::array($base)->last();
        $this->assertEquals(ESString::class, get_class($actual));
        $this->assertEquals($expected, $actual);

        $expected = ["hello", "world"];
        $actual = Shoop::array($base)->last(2);
        $this->assertEquals($expected, $actual->unfold());

        $expected = 1;
        $actual = Shoop::array([1])->last();
        $this->assertEquals($expected, $actual->unfold());

        $expected = "";
        $actual = Shoop::array([])->last();
        $this->assertEquals($expected, $actual->unfold());

        $expected = "";
        $actual = Shoop::array([])->first();
        $this->assertEquals($expected, $actual->unfold());

        $base = ["something", "hello", "world"];

        $actual = Shoop::array($base)->startsWith("something");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());

        $actual = Shoop::array($base)->startsWith(
            "something", "hello", function($result, $value) {
                if ($result->unfold()) {
                    return $value;
                }
                return false;
        });
        $this->assertSame($base, $actual->unfold());

        $base = ["hello", "world"];

        $expected = ["something", "hello", "world"];
        $actual = Shoop::array($base)->start("something");
        $this->assertEquals(ESArray::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @test
     */
    public function ESBoolean()
    {
        AssertEqualsFluent::applyWith(
            [false, true],
            ESArray::class,
            1.68
        )->unfoldUsing(
            Shoop::this(true)->asArray()
        );
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        AssertEqualsFluent::applyWith(
            ["string", true],
            ESArray::class,
            1.37
        )->unfoldUsing(
            Shoop::this(["a" => "string", "b" => true])->asArray()
        );
    }

    /**
     * @test
     */
    public function ESInteger()
    {
        AssertEqualsFluent::applyWith(
            [0, 1, 2, 3, 4, 5],
            ESArray::class,
            1.55
        )->unfoldUsing(
            Shoop::this(5)->asArray()
        );
    }

    /**
     * @test
     */
    public function ESJson()
    {
        AssertEqualsFluent::applyWith(
            ["test"],
            ESArray::class,
            2.22
        )->unfoldUsing(
            Shoop::this('{"test":"test"}')->asArray()
        );
    }

    /**
     * @test
     */
    public function ESTuple()
    {
        AssertEqualsFluent::applyWith(
            [],
            ESArray::class,
            8.49
        )->unfoldUsing(
            Shoop::this(new stdClass)->asArray()
        );
    }

    /**
     * @test
     */
    public function ESString()
    {
        AssertEqualsFluent::applyWith(
            ["h", "e", "l", "l", "o"],
            ESArray::class,
            2.32
        )->unfoldUsing(
            Shoop::this("hello")->asArray()
        );
    }
}

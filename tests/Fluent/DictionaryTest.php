<?php

namespace Eightfold\Shoop\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Shoop\Tests\AssertEqualsFluent;

class DictionaryTest extends TestCase
{
    /**
     * @test
     */
    public function divide()
    {
        $expected = ["members" => ["member", "member2"], "values" => ["value", "value2"]];
        $actual = ESDictionary::fold(["member" => "value", "member2" => "value2"])->divide();
        $this->assertEquals($expected, $actual->unfold());

        $actual = ESDictionary::fold(["member" => "value", "" => null, "empty" => "", "member2" => "value2"])->divide(0, false);
        $this->assertSame($expected, $actual->unfold());
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        // $using = ["a" => "hello", "b" => "goodbye", "c" => "hello"];
        // AssertEqualsFluent::applyWith(
        //     ["a" => "hello", "c" => "hello"],
        //     ESDictionary::class
        // )->unfoldUsing(
        //     Shoop::this($using)->minusAt(["b"])
        // );

        // $base = ["member" => "value", "member2" => "value2"];

        // $expected = ["member2" => "value2"];
        // $actual = ESDictionary::fold($base)->dropFirst();
        // $this->assertEquals($expected, $actual->unfold());

        // $base = ["member" => "value", "member2" => "value2"];

        // $expected = ["member" => "value"];
        // $actual = ESDictionary::fold($base)->dropLast();
        // $this->assertEquals($expected, $actual->unfold());

        // $base = ["member" => "value", "member2" => "value2"];

        // $expected = [];
        // $actual = ESDictionary::fold($base)->drop("member", "member2");
        // $this->assertEquals($expected, $actual->unfold());

        // $base = ["member" => false, "member2" => "value2"];

        // $expected = ["member2" => "value2"];
        // $actual = ESDictionary::fold($base)->noEmpties();
        // $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        $expected = [
            ["values" => ["value", "value2"]],
            ["values" => ["value", "value2"]],
            ["values" => ["value", "value2"]],
            ["values" => ["value", "value2"]],
            ["values" => ["value", "value2"]]
        ];
        $actual = ESDictionary::fold(["values" => ["value", "value2"]])->multiply(5);
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @return Eightfold\Shoop\ESDictionary After setting the value for the given member.
     */
    public function ESDictionary()
    {
        $base = ["member" => "value"];

        $expected = ["member" => "value", "member2" => "value2"];
        $actual = ESDictionary::fold($base)->set("value2", "member2");
        $this->assertEquals($expected, $actual->unfold());
    }

    public function ESDictionary()
    {
        $expected = '{}';
        $actual = json_encode(Shoop::dictionary([]));
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        AssertEqualsFluent::applyWith(false, 1.39)
            ->unfoldUsing(ESDictionary::fold([])->asBoolean());
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        AssertEqualsFluent::applyWith(["hello" => "world"], 1.37)
            ->unfoldUsing(
                ESDictionary::fold(["hello" => "world"])->asDictionary()
            );
    }

    public function ESDictionary()
    {
        $base = ["member" => "value"];
        $actual = ESDictionary::fold($base)->has("value");
        $this->assertTrue($actual->unfold());

        $expected = "v";
        $actual = ESDictionary::fold($base)->has(
            "value", function($result, $value) {
                if ($result->unfold()) {
                    return "v";
                }
                return null;
        });
        $this->assertSame($expected, $actual->unfold());

        $expected = "v";
        $actual = ESDictionary::fold($base)->doesNothave(
            "hi", function($result, $value) {
                if ($result->unfold()) {
                    return null;
                }
                return "v";
        });
        $this->assertSame($expected, $actual->unfold());

        $actual = ESDictionary::fold($base)->hasMember("member");
        $this->assertTrue($actual->unfold());
    }

    public function ESDictionary()
    {
        $base = ["member" => "value"];
        $actual = ESDictionary::fold($base)->hasMember("member");
        $this->assertTrue($actual->unfold());

        $actual = ESDictionary::fold($base)->hasMember(
            "member", function($result, $value) {
            return $result;
        });
        $this->assertTrue($actual->unfold());

        $actual = ESDictionary::fold($base)->doesNotHaveMember(
            "array", function($result, $value) {
            return $result;
        });
        $this->assertTrue($actual->unfold());
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        AssertEqualsFluent::applyWith(0, 1.56)
            ->unfoldUsing(ESDictionary::fold([])->asInteger());
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        AssertEqualsFluent::applyWith('{}', 2.4)
            ->unfoldUsing(ESDictionary::fold([])->asJson());
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        AssertEqualsFluent::applyWith(
            "",
            ESString::class,
            2.08
        )->unfoldUsing(
            Shoop::this([])->asString()
        );
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        AssertEqualsFluent::applyWith(new stdClass, 4.03)
            ->unfoldUsing(ESDictionary::fold([])->asTuple());
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        $base = ["zero" => 0, "first" => 1, "second" => 2];

        $actual = ESDictionary::fold($base)->doesNotEndWith(0, "zero", 1, "first");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());
    }

    public function ESDictionary()
    {
        $base = ["zero" => 0, "first" => 1, "second" => 2];

        $actual = ESDictionary::fold($base)->doesNotStartWith(0, "zero", 1, "first");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertFalse($actual->unfold());
    }

    public function ESDictionary()
    {
        $base = ["zero" => 0, "first" => 1, "second" => 2];

        $actual = ESDictionary::fold($base)->endsWith(0, "zero", 1, "first");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertFalse($actual->unfold());

        $actual = ESDictionary::fold($base)->endsWith(
            0, "zero", 1, "first", 2, "second", function($result, $value) {
                if ($result) {
                    return $value;
                }
                return false;
        });
        $this->assertSame($base, $actual->unfold());
    }

    public function ESDictionary()
    {
        $base = ["first" => 1, "second" => 2];

        $expected = ["first" => 1, "second" => 2, "zero" => 0];
        $actual = ESDictionary::fold($base)->end(0, "zero");
        $this->assertEquals(ESDictionary::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());
    }

    public function ESDictionary()
    {
        $base = ["first" => 1, "second" => "value"];

        $expected = 1;
        $actual = ESDictionary::fold($base)->first();
        $this->assertEquals(ESInteger::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());

        $expected = [1, "value"];
        $actual = ESDictionary::fold($base)->first(2);
        $this->assertEquals($expected, $actual->unfold());
    }

    public function ESDictionary()
    {
        $base = ["first" => 1, "second" => 2];

        $expected = 2;
        $actual = ESDictionary::fold($base)->last();
        $this->assertEquals(ESInteger::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());

        $base = ["first" => 1, "second" => 2, "third" => 3];
        $expected = [2, 3];
        $actual = ESDictionary::fold($base)->last(2);
        $this->assertEquals($expected, $actual->unfold());
    }

    public function ESDictionary()
    {
        $base = ["zero" => 0, "first" => 1, "second" => 2, "third" => 3];

        $actual = ESDictionary::fold($base)->startsWith(0, "zero", 1, "first");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());

        $actual = ESDictionary::fold($base)->startsWith(
            0, "zero", 1, "first", function($result, $value) {
                if ($result->unfold()) {
                    return $value;
                }
                return false;
        });
        $this->assertSame($base, $actual->unfold());
    }

    public function ESDictionary()
    {
        $base = ["first" => 1, "second" => 2];

        $expected = ["zero" => 0, "first" => 1, "second" => 2];
        $actual = ESDictionary::fold($base)->start(0, "zero");
        $this->assertEquals(ESDictionary::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());
    }
}

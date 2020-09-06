<?php

namespace Eightfold\Shoop\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Shoop\Tests\AssertEqualsFluent;

class JsonTest extends TestCase
{
    /**
     * @test
     */
    public function divide()
    {
        $expected = json_encode((object) ["members" => ["member", "member2"], "values" => ["value", "value2"]]);
        $actual = ESJson::fold('{"member":"value","member2":"value2"}')->divide();
        $this->assertEquals($expected, $actual->unfold());

        $actual = ESJson::fold('{"member":"value", "":null,"member2":"value2", "empty":""}')->divide(0, false);
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @see ESDictionary->multiply() Where each index is the original JSON string.
     */
    public function ESJson()
    {
        $json = json_encode((object) ["member" => "value", "member2" => "value2"]);
        $expected = [$json, $json, $json, $json];
        $actual = ESJson::fold('{"member":"value","member2":"value2"}')->multiply(4);
        $this->assertEquals($expected, $actual->unfold());

        $base = '{}';

        $expected = '{"test":"test"}';
        $actual = ESJson::fold($base)->set("test", "test");
        $this->assertEquals($expected, $actual->unfold());

        $json = Shoop::json('{"member":"test"}');
        $expected = new \stdClass();
        $expected->member = "test";
        $expected = json_encode($expected);
        $actual = json_encode($json);
        $this->assertEquals($expected, $actual);

        AssertEqualsFluent::applyWith(true, 2.39)
            ->unfoldUsing(ESJson::fold('{"test":"test"}')->asBoolean());

        AssertEqualsFluent::applyWith(false)
            ->unfoldUsing(ESJson::fold('{}')->asBoolean());

        AssertEqualsFluent::applyWith(["test" => true], 1.84)
            ->unfoldUsing(ESJson::fold('{"test":true}')->asDictionary());

        AssertEqualsFluent::applyWith(1, 2.31)
            ->unfoldUsing(ESJson::fold('{"test":"test"}')->asInteger());

        AssertEqualsFluent::applyWith('{"test":"test"}', 1.55)
            ->unfoldUsing(ESJson::fold('{"test":"test"}')->asJson());

        $base = '{"member":"value", "member2":"value2", "member3":"value3"}';

        $expected = '{"member3":"value3"}';
        $actual = ESJson::fold($base)->dropFirst(2);
        $this->assertEquals($expected, $actual);

        $base = '{"member":"value", "member2":"value2", "member3":"value3"}';

        $expected = '{"member":"value"}';
        $actual = ESJson::fold($base)->dropLast(2);
        $this->assertEquals($expected, $actual);

        $base = '{"member":"value", "member2":"value2", "member3":"value3"}';

        $expected = '{"member2":"value2"}';
        $actual = ESJson::fold($base)->drop("member", "member3");
        $this->assertEquals($expected, $actual);

        $base = '{"member":false, "member2":"value2", "member3":0}';

        $expected = '{"member2":"value2"}';
        $actual = ESJson::fold($base)->noEmpties();
        $this->assertEquals($expected, $actual);

        $base = '{"member":"value", "member2":"value2", "member3":"value3"}';
        $actual = ESJson::fold($base)->has("value3");
        $this->assertTrue($actual->unfold());

        $expected = "v";
        $actual = ESJson::fold($base)->has("value3", function($result, $value) {
            if ($result->unfold()) {
                return "v";
            }
            return null;
        });
        $this->assertSame($expected, $actual->unfold());

        $expected = "v";
        $actual = ESJson::fold($base)->doesNothave(
            "hi", function($result, $value) {
                if ($result->unfold()) {
                    return null;
                }
                return "v";
        });
        $this->assertSame($expected, $actual->unfold());

        $actual = ESJson::fold($base)->hasMember("member2");
        $this->assertTrue($actual->unfold());

        $base = '{"events":{"2020":{"5":{"20":[{"title": "Event at Meetup"}]}}}}';
        $actual = ESJson::fold($base)->getEvents()->hasMember("2020");
        $this->assertTrue($actual->unfold());

        $base = '{"member":"value", "member2":"value2", "member3":"value3"}';
        $actual = ESJson::fold($base)->hasMember("member2");
        $this->assertTrue($actual->unfold());

        $actual = ESJson::fold($base)->hasMember(
            "member", function($result, $value) {
            return $result;
        });
        $this->assertTrue($actual->unfold());

        $actual = ESJson::fold($base)->doesNotHaveMember(
            "member4", function($result, $value) {
            return $result;
        });
        $this->assertTrue($actual->unfold());

        AssertEqualsFluent::applyWith(
            "test",
            ESString::class,
            3.15
        )->unfoldUsing(
            Shoop::this('{"test":"test"}')->asString()
        );

        AssertEqualsFluent::applyWith((object) ["test" => "test"], 4.18)
            ->unfoldUsing(ESJson::fold('{"test":"test"}')->asTuple());

        $base = json_encode(["member" => "value", "member2" => "value2", "member3" => "value3"]);

        $actual = ESJson::fold($base)->doesNotEndWith("value3", "member3");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertFalse($actual->unfold());

        $base = json_encode(["member" => "value", "member2" => "value2", "member3" => "value3"]);

        $actual = ESJson::fold($base)->doesNotStartWith("value3", "member3");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());

        $base = json_encode(["member" => "value", "member2" => "value2", "member3" => "value3"]);

        $actual = ESJson::fold($base)->endsWith("value3", "member3");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());

        $actual = ESJson::fold($base)->endsWith(
            "member", "value", "member2", "value2", "member3", "value3",
            function($result, $value) {
                if ($result) {
                    return $value;
                }
                return false;
        });
        $this->assertSame($base, $actual->unfold());

        $base = '{"member2":"value2", "member3":"value3"}';

        $expected = json_encode(["member2" => "value2", "member3" => "value3", "member" => "value"]);
        $actual = ESJson::fold($base)->end("value", "member");
        $this->assertEquals(ESJson::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());

        $base = '{"member":"value", "member2":"value2", "member3":"value3"}';

        $expected = "value";
        $actual = ESJson::fold($base)->first();
        $this->assertEquals(ESString::class, get_class($actual));
        $this->assertEquals($expected, $actual);

        $expected = ["value", "value2", "value3"];
        $actual = ESJson::fold($base)->first(3);
        $this->assertEquals($expected, $actual->unfold());

        $base = '{"member":"value", "member2":"value2", "member3":"value3"}';

        $expected = "value3";
        $actual = ESJson::fold($base)->last();
        $this->assertEquals(ESString::class, get_class($actual));
        $this->assertEquals($expected, $actual);

        $expected = ["value2", "value3"];
        $actual = ESJson::fold($base)->last(2);
        $this->assertEquals($expected, $actual->unfold());

        $base = json_encode(["member" => "value", "member2" => "value2", "member3" => "value3"]);

        $actual = ESJson::fold($base)->startsWith("value", "member");
        $this->assertEquals(ESBoolean::class, get_class($actual));
        $this->assertTrue($actual->unfold());

        $actual = ESJson::fold($base)->startsWith(
            "value", "member", function($result, $value) {
                if ($result->unfold()) {
                    return $value;
                }
                return false;
        });
        $this->assertSame($base, $actual->unfold());

        $base = '{"member2":"value2", "member3":"value3"}';

        $expected = json_encode(["member" => "value", "member2" => "value2", "member3" => "value3"]);
        $actual = ESJson::fold($base)->start("value", "member");
        $this->assertEquals(ESJson::class, get_class($actual));
        $this->assertEquals($expected, $actual->unfold());
    }
}

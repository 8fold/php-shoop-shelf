<?php

namespace Eightfold\Shoop\Tests\Foldable;

use PHPUnit\Framework\TestCase;

use Eightfold\Shoop\Shoop;

// TODO: Make this an interface and trait in the Foldable library
class UnfoldExtensionsTest extends TestCase
{
    /**
     * @test
     */
    public function ESBoolean()
    {
        $base = Shoop::this(true);

        $expected = ["true" => true, "false" => false];
        $actual = $base->dictionary;
        $this->assertEquals($expected, $actual);

        $expected = true;
        $actual = $base->true;
        $this->assertTrue($expected, $actual);

        $expected = [true];
        $actual = Shoop::this(true)->arrayUnfolded();
        $this->assertEquals($expected, $actual);

        $actual = Shoop::this(true)->getArrayUnfolded();
        $this->assertEquals($expected, $actual);

        $base = true;
        $actual = Shoop::this($base)->set(false);
        $this->assertFalse($actual->unfold());
    }

    /**
     * @test
     */
    public function ESArray()
    {
        $base = Shoop::this([0 => "hi"]);

        $expected = ["i0" => "hi"];
        $actual = $base->dictionary;
        $this->assertEquals($expected, $actual);

        $expected = "hi";
        $actual = $base->i0;
        $this->assertEquals($expected, $actual);

        $actual = $base->first;
        $this->assertEquals($expected, $actual);

        $expected = [0 => "hi"];
        $actual = $base->plus; // requires arguments to modify value
        $this->assertEquals($expected, $actual);

        $expected = [true];
        $result = ESArray::fold([true])->getUnfolded(0);
        $this->assertTrue($result);

        $base = [false, true];
        $array = ESArray::fold($base);
        $this->assertTrue($array->get(1)->unfold());

        $actual = $array->get(0);
        $this->assertFalse($actual->unfold());

        // Which is equivalent to:
        $actual = $array->getFirst();
        $this->assertFalse($actual->unfold());

        // Which is equivalent to:
        $actual = $array->first();
        $this->assertFalse($actual->unfold());

        $expected = [true];
        $result = ESArray::fold([true])->get0Unfolded();
        $this->assertTrue($result);

        $base = [];

        $expected = [true];
        $actual = ESArray::fold($base)->set(true, 0);
        $this->assertEquals($expected, $actual->unfold());

        $expected = [false];
        $actual = ESArray::fold([true])->set(false, 0, false);
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @test
     */
    public function ESDictionary()
    {
        $base = Shoop::this(["hello" => "world"]);

        $expected = ["hello" => "world"];
        $actual = $base->dictionary;
        $this->assertEquals($expected, $actual);

        $expected = "world";
        $actual = $base->hello;
        $this->assertEquals($expected, $actual);

        $expected = [true];
        $actual = Shoop::this(true)->arrayUnfolded();
        $this->assertEquals($expected, $actual);

        $base = true;
        $actual = Shoop::this($base)->get();
        $this->assertTrue($actual->unfold());

        $actual = Shoop::this(false)->get("true");
        $this->assertFalse($actual->unfold());

        $base = ["member" => false];
        $actual = Shoop::this($base)->getUnfolded("member");
        $this->assertFalse($actual);

        $base = ["member" => false];
        $actual = Shoop::this($base)->getMember();
        $this->assertFalse($actual->unfold());

        $base = ["member" => false];
        $actual = Shoop::this($base)->member;
        $this->assertFalse($actual);

        $base = ["member" => false];
        $expected = ["member" => true];
        $actual = Shoop::this($base)->setMember(true);
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @test
     */
    public function ESInteger()
    {
        $base = Shoop::this(5);

        $expected = ["i0" => 0, "i1" => 1, "i2" => 2, "i3" => 3, "i4" => 4, "i5" => 5];
        $actual = $base->dictionary;
        $this->assertEquals($expected, $actual);

        $expected = 5;
        $actual = $base->i5;
        $this->assertEquals($expected, $actual);

        $expected = true;
        $actual = Shoop::this(1)->boolUnfolded();
        $this->assertEquals($expected, $actual);

        $base = 10;

        $expected = 0;
        $actual = Shoop::this($base)->get();
        $this->assertEquals($expected, $actual->unfold());

        $expected = 9;
        $actual = Shoop::this($base)->get(9);
        $this->assertEquals($expected, $actual->unfold());

        $base = Shoop::this(1);

        $expected = true;
        $actual = $base->bool;
        $this->assertEquals($expected, $actual);

        $expected = 1;
        $actual = $base->i1;
        $this->assertEquals($expected, $actual);

        $base = 10;
        $expected = 5;
        $actual = Shoop::this($base)->set(5);
        $this->assertEquals($expected, $actual->unfold());
    }

    /**
     * @test
     */
    public function ESString()
    {
        $base = Shoop::this("hello");

        $expected = ["i0" => "h", "i1" => "e", "i2" => "l", "i3" => "l", "i4" => "o"];
        $actual = $base->dictionary;
        $this->assertEquals($expected, $actual);

        $expected = "l";
        $actual = $base->i2;
        $this->assertEquals($expected, $actual);

        $expected = "puos tebahpla";
        $actual = Shoop::this("alphabet soup")->toggleUnfolded();
        $this->assertEquals($expected, $actual);

        $base = "alphabet soup";
        $actual = Shoop::this($base)->get(1);
        $this->assertEquals("l", $actual->unfold());

        $expected = " ";
        $actual = Shoop::this("alphabet soup")->get8Unfolded();
        $this->assertEquals($expected, $actual);

        $base = "alphabet soup";
        $actual = Shoop::this($base)->get(1);
        $this->assertEquals("l", $actual->unfold());
    }

    /**
     * @test
     */
    public function ESTuple()
    {
        $object = new \stdClass();
        $object->test = true;

        $base = Shoop::this($object);

        $expected = ["test" => true];
        $actual = $base->dictionary;
        $this->assertEquals($expected, $actual);

        $expected = true;
        $actual = $base->test;
        $this->assertEquals($expected, $actual);

        $base = new \stdClass();
        $base->test = false;
        $actual = Shoop::this($base)->testUnfolded();
        $this->assertFalse($actual);

        $base = new \stdClass();
        $base->test = false;
        $actual = Shoop::this($base)->getTest();
        $this->assertFalse($actual->unfold());

        $base = new \stdClass();
        $base->test = false;
        $actual = Shoop::this($base)->getTestUnfolded();
        $this->assertFalse($actual);

        $base = new \stdClass();
        $base->test = false;
        $actual = Shoop::this($base)->setTest(true, true);
        $this->assertTrue($actual->test()->unfold());
    }

    /**
     * @test
     */
    public function ESJson()
    {
        $base = Shoop::this('{"test":true}');

        $expected = ["test" => true];
        $actual = $base->dictionary;
        $this->assertEquals($expected, $actual);

        $expected = true;
        $actual = $base->test;
        $this->assertEquals($expected, $actual);

        $actual = Shoop::this('{"test":true}')->testUnfolded();
        $this->assertTrue($actual);

        $base = '{"test":true}';
        $actual = Shoop::this($base)->getTest();
        $this->assertTrue($actual->unfold());

        $actual = Shoop::this($base)->test();
        $this->assertTrue($actual->unfold());

        $base = Shoop::this('{"test":true}');

        $actual = $base->test;
        $this->assertTrue($actual);

        $expected = '{"test":true}';
        $actual = Shoop::this('{}')->setTest(true);
        $this->assertEquals($expected, $actual);
    }
}

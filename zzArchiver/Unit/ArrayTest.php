<?php

namespace Eightfold\Shoop\Tests\FluentTypes\Unit;

use Eightfold\Shoop\Tests\FluentTypes\Unit\UnitTestCase;
use Eightfold\Shoop\Tests\AssertEqualsFluent;

use Eightfold\Shoop\Shoop;

use Eightfold\Shoop\FluentTypes\ESArray;
use Eightfold\Shoop\FluentTypes\ESBoolean;
use Eightfold\Shoop\FluentTypes\ESDictionary;
use Eightfold\Shoop\FluentTypes\ESInteger;
use Eightfold\Shoop\FluentTypes\ESJson;
use Eightfold\Shoop\FluentTypes\ESString;
use Eightfold\Shoop\FluentTypes\ESTuple;

/**
 * @group ArrayFluentUnit
 */
class ArrayTest extends UnitTestCase
{
    static public function sutClassName(): string
    {
        return ESArray::class;
    }

    /**
     * @test
     */
    public function append()
    {
        $this->assertFalse(true);
    }

    /**
     * @test
     */
    public function prepend()
    {
        $this->assertFalse(true);
    }

    /**
     * @test
     */
    public function types()
    {
        $this->assertEquals(
            ["collection", "list", "array"],
            Shoop::this([1, 2, 3])->types()
        );
    }

// -> Array access

    /**
     * @test
     */
    public function _at()
    {
        $using = [2, 3, "four"];

        // use integer
        AssertEqualsFluent::applyWith(
            3,
            ESInteger::class,
            5.41
        )->unfoldUsing(
            Shoop::this($using)->at(1)
        );

        // use ESInteger
        AssertEqualsFluent::applyWith(
            "four",
            ESString::class,
            0.52
        )->unfoldUsing(
            Shoop::this($using)->At(
                Shoop::this(2)
            )
        );

        $this->assertEquals(2, $using[0]);
    }

    /**
     * @test
     */
    public function hasAt()
    {
        $using = [2, 3, 4];

        // use integer
        AssertEqualsFluent::applyWith(
            true,
            ESBoolean::class,
            6.44
        )->unfoldUsing(
            Shoop::this($using)->hasAt(1)
        );

        // use ESInteger
        AssertEqualsFluent::applyWith(
            false,
            ESBoolean::class,
            3.12
        )->unfoldUsing(
            Shoop::this($using)->hasAt(
                Shoop::this(3)
            )
        );

        $this->assertTrue(isset($using[1]));

        // ESDictionary
        // use string
        // use ESString
    }

    /**
     * @test
     */
    public function plusAt()
    {
        $using = [2, 4];

        // use integer
        AssertEqualsFluent::applyWith(
            [3, 4],
            ESArray::class
        )->unfoldUsing(
            Shoop::this($using)->plusAt(3, 0, true)
        );

        AssertEqualsFluent::applyWith(
            [3, 2, 4],
            ESArray::class
        )->unfoldUsing(
            Shoop::this($using)->plusAt(3, 0)
        );

        // use ESInteger
        AssertEqualsFluent::applyWith(
            [2, 4, "string"],
            ESArray::class
        )->unfoldUsing(
            Shoop::this($using)->plusAt("string")
        );

        $using = Shoop::this($using);
        $using[0] = 3;
        $this->assertEquals([3, 4], $using->unfold());
    }

    /**
     * @test
     */
    public function minusAt()
    {
        $using = [2, 3, 4];

        // use integer
        AssertEqualsFluent::applyWith(
            [3, 4],
            ESArray::class
        )->unfoldUsing(
            Shoop::this($using)->minusAt(0)
        );

        // use ESInteger
        AssertEqualsFluent::applyWith(
            [2, 3],
            ESArray::class
        )->unfoldUsing(
            Shoop::this($using)->minusAt(
                Shoop::this(2)
            )
        );

        $using = Shoop::this($using);
        unset($using[1]);
        $this->assertEquals([2, 4], $using->unfold());
    }

// -> Iterator

    /**
     * @test
     */
    public function php_iterator()
    {
        $sut = Shoop::this([1, 2, 3]);

        $keys = [];
        $sum  = 0;
        foreach ($sut as $key => $value) {
            $keys[] = $key;
            $sum += $value;
        }
        $this->assertEquals([0, 1, 2], $keys);
        $this->assertEquals(6, $sum);
    }

// -> Type juggling

    /**
     * @test
     */
    public function asArray(): void
    {
        AssertEqualsFluent::applyWith(
            [1, 2, 3],
            ESArray::class,
            13.15
        )->unfoldUsing(
            Shoop::this([1, 2, 3])->asArray()
        );
    }

    /**
     * @test
     */
    public function asBoolean(): void
    {
        AssertEqualsFluent::applyWith(
            false,
            ESBoolean::class,
            3.64
        )->unfoldUsing(
            Shoop::this([])->asBoolean()
        );
    }

    /**
     * @test
     */
    public function asDictionary(): void
    {
        AssertEqualsFluent::applyWith(
            ["i0" => "a", "i1" => "b"],
            ESDictionary::class,
            0.5
        )->unfoldUsing(
            Shoop::this(["a", "b"])->asDictionary()
        );
    }

    /**
     * @test
     */
    public function asInteger(): void
    {
        AssertEqualsFluent::applyWith(
            2,
            ESInteger::class,
            0.43
        )->unfoldUsing(
            Shoop::this(["a", "b"])->asInteger()
        );
    }

    /**
     * @test
     */
    public function asJson(): void
    {
        AssertEqualsFluent::applyWith(
            '{"i0":"a","i1":"b"}',
            ESJson::class,
            1.44
        )->unfoldUsing(
            Shoop::this(["a", "b"])->asJson()
        );
    }

    /**
     * @test
     */
    public function asString(): void
    {
        AssertEqualsFluent::applyWith(
            "ab",
            ESString::class,
            1.36
        )->unfoldUsing(
            Shoop::this(["a", "b"])->asString()
        );
    }

    /**
     * @test
     */
    public function asTuple(): void
    {
        AssertEqualsFluent::applyWith(
            (object) ["i0" => "a", "i1" => "b"],
            ESTuple::class,
            1.15
        )->unfoldUsing(
            Shoop::this(["a", "b"])->asTuple()
        );
    }
}

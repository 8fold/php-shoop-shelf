<?php

namespace Eightfold\Shoop\Tests\FluentTypes\Unit;

use PHPUnit\Framework\TestCase;

use \ReflectionClass;
use \ReflectionMethod;

abstract class UnitTestCase extends TestCase
{
    abstract static public function sutClassName(): string;

    /**
     * Ignore these methods because they are either value holders, use the basic
     * implementation from Shoop pipes, is covered by another set of test cases,
     * or some combination thereof.
     */
    static protected function ignoreClassMethods()
    {
        return [
            "args",                     // value method, returns args following or incl. main
            "efIs",                     // uses Shoop default (is)
            "efIsEmpty",                // uses Shoop default (isEmpty)
            "efIsGreaterThan",          // uses Shoop default (isEmpty)
            "efIsGreaterThanOrEqualTo", // uses Shoop default (isEmpty)
            "efIsOrdered",              // uses Shoop default (isEmpty)
            "efToArray",                // uses Shoop default (asArray)
            "efToBoolean",              // uses Shoop default (asBoolean)
            "efToDictionary",           // uses Shoop default (asDictionary)
            "efToInteger",              // uses Shoop default (asInteger)
            "efToJson",                 // uses Shoop default (asJson)
            "efToString",               // uses Shoop default (asJson)
            "efToTuple",                // uses Shoop default (asJson)
            "fold",                     // uses Foldable default
            "count",                    // uses Shoop default (efToInteger)
            "jsonSerialize",            // uses Shoop default (efToTuple)
            "offsetExists",             // uses Shoop default (hasMember)
            "offsetGet",                // uses Shoop default (at)
            "offsetSet",                // uses Shoop default (plusMember ??) TODO: Should plus() be solely for values
            "offsetUnset",              // uses Shoop default (minusMember)
            "unfold",                   // uses Foldable default
            "rewind",                   // part of php_iterator
            "valid",                    // part of php_iterator
            "current",                  // part of php_iterator
            "key",                      // part of php_iterator
            "next",                     // part of php_iterator
        ];
    }

    /**
     * @test
     */
    public function case_exists_for_each_method()
    {
        $caseMethods = array_map(
            function($reflectionMethod) {
                if (! in_array($reflectionMethod->name, ["setUp", "testsExistForEachMethod"]) and
                    $reflectionMethod->class === static::class
                ) {
                    if ($reflectionMethod->name === "_at") {
                        return "at";
                    }
                    return $reflectionMethod->name;
                }
            },
            (new ReflectionClass(static::class))->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        $caseMethods = array_values(array_filter($caseMethods));

        $sutMethods = array_map(
            function($reflectionMethod) {
                if (! in_array($reflectionMethod->name, static::ignoreClassMethods()) and
                    $reflectionMethod->name[0] !== "_"
                ) {
                    return $reflectionMethod->name;
                }
            },
            (new ReflectionClass(static::sutClassName()))
                ->getMethods(ReflectionMethod::IS_PUBLIC),
        );
        $sutMethods = array_values(array_filter($sutMethods));
        $sutMethods[] = "php_iterator";

        $notTested = array_diff($sutMethods, $caseMethods);
        sort($notTested);
        $notTestedString = print_r($notTested, true);
        $this->assertEquals(0, count($notTested), "The following methods have not been tested (only whether a test method exists): {$notTestedString}");
    }
}

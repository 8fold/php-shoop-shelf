<?php

namespace Eightfold\Shoop\Helpers;

use Eightfold\Shoop\FluentTypes\Helpers\{
    PhpTypeJuggle,
    PhpIndexedArray,
    PhpObject
};

class PhpAssociativeArray
{
    // TODO: PHP 8.0 int|string = $member
    static public function afterSettingValue(
        array $array,
        $value,
        $member,
        bool $overwrite
    ): array
    {
        if ($member === null) {
            trigger_error("Null is not a valid member on array.");
        }

        if (isset($array[$member]) and $overwrite) {
            $set = [$member => $value];
            $array = array_replace($array, $set);

        } elseif ($overwrite) {
            $set = [$member => $value];
            $array = array_replace($array, $set);

        } else {
            $array[$member] = $value;

        }
        return $array;
    }

    static public function toMembersAndValuesAssociativeArray(array $dictionary): array
    {
        $left = array_keys($dictionary);
        $right = PhpAssociativeArray::toIndexedArray($dictionary);
        $dictionary = ["members" => $left, "values" => $right];
        return $dictionary;
    }

    static public function hasMember(array $array, string $member): bool
    {
        return array_key_exists($member, $array);
    }
}

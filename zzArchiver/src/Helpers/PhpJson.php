<?php

namespace Eightfold\Shoop\Helpers;

use Eightfold\Shoop\FluentTypes\Helpers\{
    PhpTypeJuggle,
    PhpAssociativeArray,
    PhpObject
};

class PhpJson
{
    static public function afterSettingValue(string $json, $value, $member, bool $overwrite = true): string
    {
        self::isJson($json);
        $object = self::toObject($json);
        $object = PhpObject::afterSettingValue($object, $value, $member, $overwrite);
        $json = PhpObject::toJson($object);
        return $json;
    }

    static public function hasMember(string $json, string $member): bool
    {
        self::isJson($json);
        $object = self::toObject($json);
        return property_exists($object, $member);
    }
}

<?php

namespace Eightfold\Shoop\Helpers;

use Eightfold\Shoop\FluentTypes\Helpers\{
    PhpTypeJuggle,
    PhpIndexedArray,
    PhpAssociativeArray
};

class PhpObject
{
    static public function afterRemovingMembers(object $object, array $members): object
    {
        foreach ($members as $member) {
            if (method_exists($object, $member) or property_exists($object, $member)) {
                unset($object->{$member});
            }
        }
        return $object;
    }

    static public function hasMember(\stdClass $object, string $member): bool
    {
        return property_exists($object, $member);
    }
}

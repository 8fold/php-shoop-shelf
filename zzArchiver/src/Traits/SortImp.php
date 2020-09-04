<?php

namespace Eightfold\Shoop\FluentTypes\Traits;


use Eightfold\Shoop\FluentTypes\Helpers\{
    PhpTypeJuggle,
    PhpIndexedArray,
    PhpAssociativeArray,
    PhpObject
};

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\FluentTypes\{
    ESArray,
    ESBoolean,
    ESInteger,
    ESString,
    ESTuple,
    ESJson,
    ESDictionary
};

trait SortImp
{
    public function sort($asc = true, $caseSensitive = true): Foldable
    {
        if (Type::is($this, ESArray::class)) {
            $array = $this->arrayUnfolded();
            $array = PhpIndexedArray::toSortedIndexedArray($array, $asc, $caseSensitive);
            return Shoop::array($array);

        } elseif (Type::is($this, ESDictionary::class)) {
            $array = $this->dictionaryUnfolded();
            $dictionary = PhpAssociativeArray::toSortedAssociativeArray($array, $asc, $caseSensitive);
            return Shoop::dictionary($dictionary);

        } elseif (Type::is($this, ESJson::class)) {
            $object = $this->objectUnfolded();
            $object = PhpObject::toSortedObject($object, $asc, $caseSensitive);
            $json = PhpObject::toJson($object);
            return Shoop::json($json);

        } elseif (Type::is($this, ESTuple::class)) {
            $object = $this->objectUnfolded();
            $object = PhpObject::toSortedObject($object, $asc, $caseSensitive);
            return Shoop::object($object);

        } elseif (Type::is($this, ESString::class)) {
            $array = $this->arrayUnfolded();
            $array = PhpIndexedArray::toSortedIndexedArray($array, $asc, $caseSensitive);
            $string = implode("", $array);
            return Shoop::string($string);

        }
    }

    public function sortMembers($asc = true, $caseSensitive = true): Foldable
    {
        if (Type::is($this, ESArray::class)) {
            $array = $this->arrayUnfolded();
            $array = PhpIndexedArray::toSortedIndexedArray($array, $asc, $caseSensitive, true);
            return Shoop::array($array);

        } elseif (Type::is($this, ESDictionary::class)) {
            $array = $this->dictionaryUnfolded();
            $dictionary = PhpAssociativeArray::toSortedAssociativeArray($array, $asc, $caseSensitive, true);
            return Shoop::dictionary($dictionary);

        } elseif (Type::is($this, ESJson::class)) {
            $object = $this->objectUnfolded();
            $object = PhpObject::toSortedObject($object, $asc, $caseSensitive, true);
            $json = PhpObject::toJson($object);
            return Shoop::json($json);

        } elseif (Type::is($this, ESTuple::class)) {
            $object = $this->objectUnfolded();
            $object = PhpObject::toSortedObject($object, $asc, $caseSensitive, true);
            return Shoop::object($object);

        } elseif (Type::is($this, ESString::class)) {
            $array = $this->arrayUnfolded();
            $array = PhpIndexedArray::toSortedIndexedArray($array, $asc, $caseSensitive, true);
            $string = implode("", $array);
            return Shoop::string($string);

        }
    }
}

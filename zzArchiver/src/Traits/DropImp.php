<?php

namespace Eightfold\Shoop\FluentTypes\Traits;

use Eightfold\Shoop\FluentTypes\Helpers\{

    PhpIndexedArray,
    PhpAssociativeArray
};

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\FluentTypes\{
    Interfaces\Shooped,
    ESArray,
    ESBoolean,
    ESInteger,
    ESString,
    ESTuple,
    ESJson,
    ESDictionary
};

trait DropImp
{
    public function drop(...$members): Shooped
    {
        if (Type::is($this, ESArray::class, ESDictionary::class, ESJson::class, ESTuple::class)) {
            foreach ($members as $member) {
                $this->offsetUnset($member);
            }
            return $this;

        } elseif (Type::is($this, ESString::class)) {
            $array = $this->arrayUnfolded();
            foreach ($members as $member) {
                if (array_key_exists($member, $array)) {
                    unset($array[$member]);
                }
            }
            $string = implode("", $array);
            return Shoop::string($string);
        }
    }

    public function dropFirst($length = 1): Shooped
    {
        $length = Type::sanitizeType($length, ESInteger::class)->unfold();
        if (Type::is($this, ESArray::class)) {
            $array = $this->arrayUnfolded();
            $array = PhpIndexedArray::afterDropping($array, $length);
            return Shoop::array($array);

        } elseif (Type::is($this, ESDictionary::class)) {
            $array = $this->dictionaryUnfolded();
            $array = PhpAssociativeArray::afterDropping($array, $length);
            return Shoop::dictionary($array);

        } elseif (Type::is($this, ESJson::class)) {
            $array = $this->dictionaryUnfolded();
            $array = PhpAssociativeArray::afterDropping($array, $length);
            $json = PhpAssociativeArray::toJson($array);
            return Shoop::json($json);

        } elseif (Type::is($this, ESTuple::class)) {
            $array = $this->dictionaryUnfolded();
            $array = PhpAssociativeArray::afterDropping($array, $length);
            $object = PhpAssociativeArray::toObject($array);
            return Shoop::object($object);

        } elseif (Type::is($this, ESString::class)) {
            $array = $this->arrayUnfolded();
            $array = PhpAssociativeArray::afterDropping($array, $length);
            $string = implode("", $array);
            return Shoop::string($string);

        }
    }

    public function dropLast($length = 1): Shooped
    {
        $length = Type::sanitizeType($length, ESInteger::class)->unfold();
        if (Type::is($this, ESArray::class)) {
            $array = $this->main();
            $array = PhpIndexedArray::afterDropping($array, -$length);
            return Shoop::array($array);

        } elseif (Type::is($this, ESDictionary::class)) {
            $array = $this->dictionaryUnfolded();
            $array = PhpAssociativeArray::afterDropping($array, -$length);
            return Shoop::dictionary($array);

        } elseif (Type::is($this, ESJson::class)) {
            $array = $this->dictionaryUnfolded();
            $array = PhpAssociativeArray::afterDropping($array, -$length);
            $json = PhpAssociativeArray::toJson($array);
            return Shoop::json($json);

        } elseif (Type::is($this, ESTuple::class)) {
            $array = $this->dictionaryUnfolded();
            $array = PhpAssociativeArray::afterDropping($array, -$length);
            $object = PhpAssociativeArray::toObject($array);
            return Shoop::object($object);

        } elseif (Type::is($this, ESString::class)) {
            $array = $this->arrayUnfolded();
            $array = PhpIndexedArray::afterDropping($array, -$length);
            $string = implode("", $array);
            return Shoop::string($string);

        }
    }

    public function noEmpties(): Shooped
    {
        if (Type::is($this, ESArray::class)) {
            $array = $this->arrayUnfolded();
            $array = PhpIndexedArray::afterDroppingEmpties($array);
            return Shoop::array($array);

        } elseif (Type::is($this, ESDictionary::class)) {
            $array = $this->dictionaryUnfolded();
            $array = PhpAssociativeArray::afterDroppingEmpties($array);
            return Shoop::dictionary($array);

        } elseif (Type::is($this, ESJson::class)) {
            $array = $this->dictionaryUnfolded();
            $array = PhpAssociativeArray::afterDroppingEmpties($array);
            $json = PhpAssociativeArray::toJson($array);
            return Shoop::json($json);

        } elseif (Type::is($this, ESTuple::class)) {
            $array = $this->dictionaryUnfolded();
            $array = PhpAssociativeArray::afterDroppingEmpties($array);
            $object = PhpAssociativeArray::toObject($array);
            return Shoop::object($object);

        } elseif (Type::is($this, ESString::class)) {
            $array = $this->arrayUnfolded();
            $array = PhpIndexedArray::afterDroppingEmpties($array);
            $string = implode("", $array);
            $string = preg_replace('/\s/', '', $string);
            return Shoop::string($string);

        }
    }
}

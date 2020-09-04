<?php

namespace Eightfold\Shoop\Helpers;

use Eightfold\Shoop\Foldable\Foldable;
use Eightfold\Shoop\FluentTypes\Contracts\Shooped;

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\FluentTypes\{
    ESBoolean,
    ESInteger,
    ESString,
    ESArray,
    ESDictionary,
    ESTuple,
    ESJson
};

class Type
{
    static public function sanitizeType($toSanitize, string $shoopType = "")
    {
        if (self::isShooped($toSanitize) and strlen($shoopType) > 0) {
            $toSanitize = $toSanitize->unfold();

        } elseif (self::isShooped($toSanitize)) {
            return $toSanitize;

        } elseif (self::isNotPhp($toSanitize) and self::isNotShooped($toSanitize)) {
            return $toSanitize;

        }

        $shoopedType = self::shoopFor($toSanitize);
        $shooped = $shoopedType::fold($toSanitize);
        // TODO: See how to remove this in favor of the instance juggling
        switch ($shoopType) {
            case ESArray::class:
                return $shooped->array();
                break;

            case ESBoolean::class:
                return $shooped->bool();
                break;

            case ESDictionary::class:
                return $shooped->dictionary();
                break;

            case ESInteger::class:
                return $shooped->integer();
                break;

            case ESJson::class:
                return $shooped->json();
                break;

            case ESTuple::class:
                return $shooped->object();
                break;

            case ESString::class:
                if (self::isArray($toSanitize)) {
                    return $shooped->asString("");
                }
                return $shooped->string();
                break;

            default:
                return $shooped;
                break;
        }
    }

    static public function isFoldable($potential): bool
    {
        return $potential instanceOf Foldable;
    }

    static public function isNotFoldable($potential): bool
    {
        return ! static::isFoldable($potential);
    }

    static public function isShooped($potential): bool
    {
        return $potential instanceOf Shooped;
    }

    static public function isNotShooped($potential): bool
    {
        return ! static::isShooped($potential);
    }

    static public function shoopFor($potential): string
    {
        if (static::isShooped($potential)) {
            return get_class($potential);
        }

        if (self::isArray($potential)) {
            return ESArray::class;

        } elseif (self::isBool($potential)) {
            return ESBoolean::class;

        } elseif (self::isDictionary($potential)) {
            return ESDictionary::class;

        } elseif (self::isInt($potential)) {
            return ESInteger::class;

        } elseif (self::isJson($potential)) {
            return ESJson::class;

        } elseif (self::isObject($potential)) {
            return ESTuple::class;

        } elseif (self::isString($potential)) {
            return ESString::class;

        }
    }

    static public function is($potential, string ...$types): bool
    {
        $potentialType = self::for($potential);
        foreach ($types as $type) {
            if ($potentialType === $type) {
                return true;
            }
        }
        return false;
    }

    static public function isEmpty($check): bool
    {
        if (self::isShooped($check)) {
            $check = $check->unfold();
        }

        if (self::isJson($check) and $check === "{}") {
            return true;
        }
        return empty($check);
    }

    static public function isNotEmpty(Shooped $check): bool
    {
        return ! self::isEmpty($check);
    }

    static public function isArray($potential): bool
    {
        if (! is_array($potential)) {
            return false;

        } elseif (self::isShooped($potential) && ! is_a($potential, ESArray::class)) {
            return false;

        } elseif (self::isShooped($potential) && is_a($potential, ESArray::class)) {
            return true;

        } elseif (is_array($potential) && count($potential) === 0) {
            return true;

        } elseif (is_array($potential)) {
            $members = array_keys($potential);
            $firstMember = array_shift($members);
            if (is_int($firstMember)) {
                return true;

            } elseif (is_string($firstMember)) {
                return false;

            }
        }
        return false;
    }

    static public function isNotArray($potential): bool
    {
        return ! static::isArray($potential);
    }

    static public function isBool($potential): bool
    {
        return is_bool($potential);
    }

    static public function isNotBool($potential): bool
    {
        return ! self::isBool($potential);
    }

    static public function isDictionary($potential): bool
    {
        if (! is_array($potential)) {
            return false;

        } elseif (self::isShooped($potential) && ! is_a($potential, ESDictionary::class)) {
            return false;

        } elseif (Self::isShooped($potential) && is_a($potential, ESDictionary::class)) {
            return true;

        } elseif (is_array($potential) && count($potential) === 0) {
            return false;

        } elseif (is_array($potential)) {
            $members = array_keys($potential);
            $firstMember = array_shift($members);
            if (is_int($firstMember)) {
                return false;

            } elseif (is_string($firstMember)) {
                return true;

            }
        }
        return false;
    }

    static public function isNotDictionary($potential): bool
    {
        return ! self::isDictionary($potential);
    }

    static public function isInt($potential): bool
    {
        return is_int($potential);
    }

    static public function isNotint($potential): bool
    {
        return ! self::isInt($potential);
    }

    static public function isJson($potential): bool
    {
        $isString = is_string($potential);
        if ($isString) {
            // Bail as soon as possible.
            $potential = trim($potential);

            $startsWith = "{";
            $startsWithLength = strlen($startsWith);
            if (substr($potential, 0, $startsWithLength) !== $startsWith) {
                return false;
            }

            $endsWith = "}";
            $endsWithLength = strlen($endsWith);
            if (substr($potential, -$endsWithLength) !== $endsWith) {
                return false;
            }

            if (! is_array(json_decode($potential, true))) {
                return false;
            }

            $jsonError = json_last_error() !== JSON_ERROR_NONE;
            if ($jsonError) {
                return false;
            }
        }
        return $isString;
    }

    static public function isNotJson($potential): bool
    {
        return ! self::isJson($potential);
    }

    static public function isObject($potential): bool
    {
        return (is_object($potential) && self::isPhp($potential))
            || (self::isShooped($potential) && is_a($potential, ESTuple::class));
    }

    static public function isNotObject($potential): bool
    {
        return ! self::isObject($potential);
    }

    static public function isString($potential): bool
    {
        return is_string($potential);
    }

    static public function isNotString($potential): bool
    {
        return ! self::isString($potential);
    }

    static private function isPhp($potential): bool
    {
        if (self::isShooped($potential)) {
            return false;
        }

        $phpTypes = array_keys(self::map());
        $type = gettype($potential);
        if ($type !== "object" && in_array($type, $phpTypes)) {
            return true;
        }

        $potentialClass = get_class($potential);
        if ($potentialClass === "stdClass") {
            return true;
        }
        return false;
    }

    static private function isNotPhp($potential): bool
    {
        return ! static::isPhp($potential);
    }

    static public function for($potential): string
    {
        if (static::isShooped($potential)) {
            return get_class($potential);
        }

        $type = gettype($potential);

        if ($type === "object" && ! is_a($potential, \stdClass::class)) {
            return get_class($potential);
        }

        if ($type === "integer") {
            $type = "int";

        } elseif ($type === "boolean") {
            $type = "bool";

        } elseif (self::isJson($potential)) {
            $type = "json";

        } elseif ($type === "array" && self::isDictionary($potential)) {
            $type = "dictionary";

        }
        return $type;
    }

    static private function map(): array
    {
        return [
            "bool"       => ESBoolean::class,
            "boolean"    => ESBoolean::class,
            "int"        => ESInteger::class,
            "integer"    => ESInteger::class,
            "string"     => ESString::class,
            "array"      => ESArray::class,
            "dictionary" => ESDictionary::class,
            "object"     => ESTuple::class,
            "json"       => ESJson::class
        ];
    }

    static private function shoopClasses(): array
    {
        return array_values(static::map());
    }
}

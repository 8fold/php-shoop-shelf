<?php

namespace Eightfold\ShoopShelf;

use Eightfold\Shoop\Apply as BaseApply;

use Eightfold\Foldable\Filterable;

class Apply extends BaseApply
{
    // TODO: rewrite in Foldable - ??
    static public function __callStatic(string $filterName, array $arguments = [])
    {
        $className = static::classNameForFilter($filterName);
        return (count($arguments) === 0)
            ? $className::apply()
            : $className::applyWith(...$arguments);
    }

    // TODO: Add to Foldabel - ??
    static public function classNameForFilter($filterName)
    {
        $filterName = ucfirst($filterName);
        $className  = __NAMESPACE__ ."\\Filter\\". $filterName;
        if (static::filterClassExists($className)) {
            return $className;
        }

        // TODO: Add classNameForFilter to Shoop + make abstract method on Foldable\\Apply
        $namespace = parent::rootNameSpaceForFilters();
        return $namespace ."\\". $filterName;
    }

    // TODO: deprecate in Foldable - ??
    // static public function filterClassExists($className)
    // {
    //     return class_exists($className) and
    //         in_array(Filterable::class, class_implements($className));
    // }

    // TODO: deprecate in Foldable - ??
    static public function rootNameSpaceForFilters()
    {
        return __NAMESPACE__ ."\\Filter";
    }
}

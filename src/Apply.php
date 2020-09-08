<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf;

use Eightfold\Shoop\Apply as BaseApply;

use Eightfold\Foldable\Filterable;

class Apply extends BaseApply
{
    static public function classNameForFilter($filterName)
    {
        $filterName = ucfirst($filterName);
        $className  = __NAMESPACE__ ."\\Filter\\". $filterName;
        if (parent::filterClassExists($className)) {
            return $className;
        }

        $namespace = parent::rootNameSpaceForFilters();
        return $namespace ."\\". $filterName;
    }
}

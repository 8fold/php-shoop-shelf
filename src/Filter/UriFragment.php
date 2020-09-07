<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\Shoop;

class UriFragment extends Filter
{
    private $fragmentPrefix = "#";
    private $includePrefix  = false;

    public function __construct($includePrefix = false)
    {
        if (is_a($includePrefix, Foldable::class)) {
            $includePrefix = $includePrefix->unfold();
        }
        $this->includePrefix = $includePrefix;
    }

    public function __invoke($using): string
    {
        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        $array = Shoop::this($using)->asArray($this->fragmentPrefix, false, 2);

        $fragment = "";
        if ($array->asInteger()->is(2)->efToBoolean()) {
            return ($this->includePrefix)
                ? $this->fragmentPrefix . $array[1]
                : $array[1];

        }
        return $fragment;
    }
}

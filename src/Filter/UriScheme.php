<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\Shoop;

class UriScheme extends Filter
{
    private $schemeDivider = ":";
    private $includeDivider = false;

    public function __construct($includeDivider = false)
    {
        if (is_a($includeDivider, Foldable::class)) {
            $includeDivider = $includeDivider->unfold();
        }
        $this->includeDivider = $includeDivider;
    }

    public function __invoke($using): string
    {
        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        $array = Shoop::this($using)->asArray($this->schemeDivider, false, 2);

        if ($array->asInteger()->is(2)->efToBoolean()) {
            return ($this->includeDivider)
                ? $array[0] . $this->schemeDivider
                : $array[0];

        }
        return  "";
    }
}

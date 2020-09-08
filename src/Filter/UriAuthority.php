<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;

use Eightfold\Foldable\Foldable;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\Apply;

class UriAuthority extends Filter
{
    protected $authorityDivider  = "//";
    protected $pathDelimiter     = "/";

    private $includeDivider = false;
    private $asString = true;

    public function __construct(
        $includeDivider = false,
        $asString = true,
        $pathDelimiter = "/"
    )
    {
        if (is_a($includeDivider, Foldable::class)) {
            $includeDivider = $includeDivider->unfold();
        }
        $this->includeDivider = $includeDivider;

        if (is_a($asString, Foldable::class)) {
            $asString = $asString->unfold();
        }
        $this->asString = $asString;

        if (is_a($pathDelimiter, Foldable::class)) {
            $pathDelimiter = $pathDelimiter->unfold();
        }
        $this->pathDelimiter = $pathDelimiter;
    }

    public function __invoke($using)// : string|array
    {
        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        $array = Shoop::this($using)->asArray($this->authorityDivider, false, 2);
        if ($array->asInteger()->is(2)->efToBoolean()) {
            $array = $array->at(1)->asArray($this->pathDelimiter, true, 2);
            if ($array->asInteger()->is(2)->efToBoolean()) {
                if ($this->includeDivider) {
                    return $this->authorityDivider . $array->at(0)->unfold();

                }
                return $array->at(0)->unfold();

            }
        }
        return "";

        // if ($array->asInteger()->is(2)->efToBoolean()) {
        //     $fragmentString = Apply::uriFragment(true)->unfoldUsing($using);

        //     $queryString = Shoop::this($array[1])
        //         ->minus([$fragmentString], false, false);

        //     if ($this->asString and $this->includeDivider) {
        //         return Apply::prepend($queryString)
        //             ->unfoldUsing($this->queryDivider);

        //     } elseif ($this->asString and ! $this->includeDivider) {
        //         return $queryString->unfold();

        //     } elseif (! $this->asString) {
        //         $dictionary = [];
        //         $inter = $queryString->asArray($this->paramDivider, false);
        //         foreach ($inter as $queryParam) {
        //             list($param, $value) = Shoop::this($queryParam)->asArray("=", false, 2)->unfold();
        //             $dictionary[$param] = $value;

        //         }
        //         return $dictionary;

        //     }
        // }
        // return "";
    }
}

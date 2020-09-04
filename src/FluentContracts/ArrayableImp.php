<?php

namespace Eightfold\Shoop\FluentTypes\Contracts;

use Eightfold\Foldable\Foldable;

use Eightfold\Shoop\FilterContracts\ArrayableImp as PipeArrayableImp;

use Eightfold\Shoop\Shoop;
use Eightfold\Shoop\Apply;

use Eightfold\Shoop\FluentTypes\ESArray;
use Eightfold\Shoop\FluentTypes\ESBoolean;
use Eightfold\Shoop\FluentTypes\ESInteger;
use Eightfold\Shoop\FluentTypes\ESString;

trait ArrayableImp
{
    use PipeArrayableImp;

    public function has($needle)
    {
        $array = $this->arrayUnfolded();
        $bool = in_array($needle, $array);
        return Shoop::this($this->condition($bool, $closure));
    }

    public function at($member)
    {
        if (is_a($member, Foldable::class)) {
            $member = $member->unfold();
        }

        return Shoop::this(
            Apply::at($member)->unfoldUsing($this->main)
        );
    }

    public function hasAt($member)
    {
        return Shoop::this(
            Apply::hasMembers($member)->unfoldUsing($this->main)
        );
    }

    public function plusAt(
        $value,
        $member = PHP_INT_MAX,
        bool $overwrite = false
    )
    {
        if ($overwrite) {
            $this->main[$member] = $value;

        } else {
            if (Apply::typeIs("integer")->unfoldUsing($member) and
                $member > Apply::typeAsInteger($this->main)
            ) {
                $this->main[] = $value;

            } else {
                array_unshift($this->main, $value);

            }

        }
        $this->main = array_values($this->main);
        return $this;
    }

    public function minusAt($member)
    {
        if (is_a($member, Foldable::class)) {
            $member = $member->unfold();
        }

        unset($this->main[$member]);
        $this->main = array_values($this->main);
        return $this;
    }
}

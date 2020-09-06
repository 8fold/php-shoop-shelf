<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;
use Eightfold\Foldable\Foldable;

class Prepend extends Filter
{
    public function __invoke($using): bool
    {
        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        if (is_a($this->main, Foldable::class)) {
            $this->main = $this->main->unfold();
        }

        return $using . $this->main;
    }
}

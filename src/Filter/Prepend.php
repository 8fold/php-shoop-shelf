<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;
use Eightfold\Foldable\Foldable;

use Eightfold\ShoopShelf\Apply;

class Prepend extends Filter
{
    public function __invoke($using)
    {
        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        if (is_a($this->main, Foldable::class)) {
            $this->main = $this->main->unfold();
        }

        return Apply::append($using)->unfoldUsing($this->main);
    }
}

<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;

use Eightfold\Shoop\Shoop;

use Eightfold\Foldable\Foldable;

class IsFolder extends Filter
{
    public function __invoke($using): bool
    {
        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }
        return is_dir($using);
    }
}

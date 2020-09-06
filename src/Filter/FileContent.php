<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;
use Eightfold\Foldable\Foldable;

use Eightfold\ShoopShelf\Apply;

class FileContent extends Filter
{
    public function __invoke($using): string
    {
        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        if (! Apply::isFile()->unfoldUsing($using)) {
            return "";
        }
        return file_get_contents($using);
    }
}

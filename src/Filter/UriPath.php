<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;

use Eightfold\Foldable\Foldable;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\Apply;

class UriPath extends Filter
{
    public function __invoke($using): string
    {
        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        return Shoop::this($using)
            ->minus([
                Apply::uriScheme(true)->unfoldUsing($using),
                Apply::uriAuthority(true)->unfoldUsing($using),
                Apply::uriQuery(true)->unfoldUsing($using),
                Apply::uriFragment(true)->unfoldUsing($using)
            ], false, false)->unfold();
    }
}

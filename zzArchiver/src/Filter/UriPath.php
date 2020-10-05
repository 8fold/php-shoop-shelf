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

        $scheme    = Apply::uriScheme(true)->unfoldUsing($using);
        $authority = Apply::uriAuthority(true)->unfoldUsing($using);
        $query     = Apply::uriQuery(true)->unfoldUsing($using);
        $fragment  = Apply::uriFragment(true)->unfoldUsing($using);
die(var_dump(
    $authority
));
        return str_replace(
            [$scheme, $authority, $query, $fragment],
            ["", "", "", ""],
            $using
        );
        // return Shoop::this($using)
        //     ->drop([
        //         ,
        //         ,
        //         ,

        //     ], false, false)->unfold();
    }
}

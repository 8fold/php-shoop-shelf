<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;
use Eightfold\Foldable\Foldable;

use Eightfold\ShoopShelf\Apply;

class DeleteFolder extends Filter
{
    public function __invoke($using): bool
    {
        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        $content = Apply::folderContent(true, true)->unfoldUsing($using);
        foreach ($content as $path) {
            if (Apply::isFile()->unfoldUsing($path)) {
                Apply::deleteFile()->unfoldUsing($path);

            } elseif (Apply::isFolder()->unfoldUsing($path)) {
                Apply::deleteFolder()->unfoldUsing($path);

            }
        }
        return rmdir($using);
    }
}

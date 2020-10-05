<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;
use Eightfold\Foldable\Foldable;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\Apply;

class SaveContentToFile extends Filter
{
    private $content    = "";
    private $path       = "";
    private $makeFolder = true;

    public function __construct($content, $path, $makeFolder = true)
    {
        $this->content    = $content;
        $this->path       = $path;
        $this->makeFolder = $makeFolder;
    }

    public function __invoke($using): bool
    {
        if (is_a($this->content, Foldable::class)) {
            $this->content = $this->content->unfold();
        }

        if (is_a($this->path, Foldable::class)) {
            $this->path = $this->path->unfold();
        }

        if (is_a($this->makeFolder, Foldable::class)) {
            $this->makeFolder = $this->makeFolder->unfold();
        }

        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        $folderPath = Shoop::this($using)->divide("/")->droplast()->efToString("/");
        if (! Apply::isFile()->unfoldUsing($using) and
            ! Apply::isFolder()->unfoldUsing($folderPath) and
            $this->makeFolder
        ) {
            Apply::saveFolder()->unfoldUsing($folderPath);
        }

        $save = (int) file_put_contents($this->path, $this->content);
        return (bool) $save;
    }
}

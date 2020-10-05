<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;
use Eightfold\Foldable\Foldable;

use Eightfold\ShoopShelf\Apply;

class FolderContent extends Filter
{
    private $includeFiles   = true;
    private $includeFolders = true;
    private $ignore         = [".", "..", ".DS_Store"];

    public function __construct(
        bool $includeFiles = true,
        bool $includeFolders = true,
        array $ignore = [".", "..", ".DS_Store"]
    )
    {
        $this->includeFiles   = $includeFiles;
        $this->includeFolders = $includeFolders;
        $this->ignore         = $ignore;
    }

    public function __invoke($using): array
    {
        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        $content = scandir($using);
        $content = array_map(function($v) use ($using) {
            if (! in_array($v, $this->ignore)) {
                return $using ."/". $v;
            }
        }, $content);
        $content = array_filter($content);

        if ($this->includeFiles and ! $this->includeFolders) {
            $content = array_filter($content, function($v) {
                return Apply::isFile()->unfoldUsing($v);
            });

        } elseif (! $this->includeFiles and $this->includeFolders) {
            $content = array_filter($content, function($v) {
                return Apply::isFolder()->unfoldUsing($v);
            });

        }
        return array_values($content);
    }
}

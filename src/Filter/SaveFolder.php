<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\Filter;

use Eightfold\Foldable\Filter;
use Eightfold\Foldable\Foldable;

class SaveFolder extends Filter
{
    private $mode      = "";
    private $recursive = "";

    public function __construct($mode = 0755, $recursive = true)
    {
        $this->mode      = $mode;
        $this->recursive = $recursive;
    }

    public function __invoke($using): bool
    {
        if (is_a($this->mode, Foldable::class)) {
            $this->mode = $this->mode->unfold();
        }

        if (is_a($this->recursive, Foldable::class)) {
            $this->recursive = $this->recursive->unfold();
        }

        if (is_a($using, Foldable::class)) {
            $using = $using->unfold();
        }

        return mkdir($using, $this->mode, $this->recursive);
    }
}

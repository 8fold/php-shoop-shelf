<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Foldable\Foldable;
use Eightfold\Foldable\FoldableImp;

use Eightfold\ShoopShelf\Shoop;

class ESScheme implements Foldable
{
    use FoldableImp;

    public function main(): ESString
    {
        return Shoop::string($this->main);
    }
}

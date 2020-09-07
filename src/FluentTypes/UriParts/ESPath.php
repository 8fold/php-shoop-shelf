<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Foldable\Foldable;
use Eightfold\Foldable\FoldableImp;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\Apply;

class ESPath implements Foldable
{
    use FoldableImp;

    public function main(): ESString
    {
        return Shoop::string($this->main);
    }

    private function delimiter(): ESString
    {
        return (isset($this->args[0]))
            ? Shoop::string($this->args[0])
            : Shoop::string("/");
    }

    public function plus(...$parts): ESPath
    {
        $delimiter = $this->delimiter()->unfold();
        $path = Shoop::pipe($this->main()->unfold(),
            Apply::typeAsArray($delimiter),
            Apply::plus($parts),
            Apply::typeAsString($delimiter)
        )->unfold();

        return static::fold($path);
    }

// - Potential interface usage
    public function asArray(
        $start = 0, // int|string
        bool $includeEmpties = true,
        int $limit = PHP_INT_MAX
    )
    {
        # code...
    }
}

<?php

namespace Eightfold\ShoopExtras;

use Eightfold\ShoopExtras\ESPath;

use Eightfold\Shoop\Helpers\Type;
use Eightfold\Shoop\Interfaces\Foldable;
use Eightfold\Shoop\Traits\FoldableImp;
use Eightfold\Shoop\{
    ESString,
    ESArray,
    ESBool
};

use Eightfold\ShoopExtras\Shoop;

/**
 * https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#/media/File:URI_syntax_diagram.svg
 *
 * {scheme} - : -˅---------------------------------------------------^ {path} -˅---------- ----^ -˅ -----------------^ -->
 *               ˅- // -˅ -----------------^- {host} -˅--------------^         ˅- ? - {query} -^  ˅- # - {fragment} -^
 *                      ˅- {userinfo} - @ -^          ˅- : - {port} -^
 */
class ESUri implements Foldable
{
    use FoldableImp;

    static public function processedMain($main)
    {
        return Type::sanitizeType($main, ESString::class)->unfold();
    }

    private function schemeDivider(): string
    {
        return (isset($this->args[0])) ? $this->args[0] : ":";
    }

    protected function pathDelimiter(): string
    {
        return (isset($this->args[1])) ? $this->args[1] : "/";
    }

    public function scheme(): ESString
    {
        return $this->string()->divide($this->schemeDivider(), false, 2)
            ->countIsLessThan(2, function($result, $split) {
                return ($result->unfold())
                    ? Shoop::string("")
                    : $split->first();
            });
    }

    public function path(bool $withExtras = true): ESPath
    {
        return $this->string()->divide($this->schemeDivider(), false, 2)
            ->countIsLessThan(2, function($result, $split) {
                return ($result->unfold())
                    ? Shoop::path("")
                    : Shoop::path($split->last);
            });
    }

    public function string()
    {
        return Shoop::string($this->main());
    }
}

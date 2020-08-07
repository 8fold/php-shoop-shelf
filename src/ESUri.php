<?php

namespace Eightfold\ShoopExtras;

use Eightfold\ShoopExtras\ESPath;

use Eightfold\Shoop\Helpers\Type;
use Eightfold\Shoop\Interfaces\Shooped;
use Eightfold\Shoop\Traits\ShoopedImp;
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
class ESUri
{
    protected $raw = "";
    protected $schemeDivider = ":";
    protected $pathDelimiter = "/";

    static public function fold($main, $schemeDivider = ":", $pathDelimiter = "/")
    {
        return new static($main, $schemeDivider = ":", $pathDelimiter = "/");
    }

    public function __construct($raw, $schemeDivider = ":", $pathDelimiter = "/")
    {
        $this->raw = Shoop::string($raw);
        $this->schemeDivider = $schemeDivider;
        $this->pathDelimiter = $pathDelimiter;
    }

    public function scheme()
    {
        return $this->value()->divide($this->schemeDivider, false, 2)->countIsLessThan(2, function($result, $split) {
            return ($result->unfold()) ? Shoop::string("") : $split->first();
        });
    }

    public function path(bool $withExtras = true)
    {
        return $this->value()->divide($this->schemeDivider, false, 2)->countIsLessThan(2, function($result, $split) {
            return ($result->unfold()) ? Shoop::string("") : $split->last();
        });
    }

    public function value()
    {
        return $this->raw;
    }

    public function unfold()
    {
        return $this->value();
    }
}

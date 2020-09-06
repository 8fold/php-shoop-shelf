<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Foldable\Foldable;
use Eightfold\Foldable\FoldableImp;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\Shoop\FilterContracts\Interfaces\Associable;
use Eightfold\Shoop\FilterContracts\Interfaces\Falsifiable;
use Eightfold\Shoop\FilterContracts\Interfaces\Addable;
use Eightfold\Shoop\FilterContracts\Interfaces\Subtractable;

/**
 * https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#/media/File:URI_syntax_diagram.svg
 *
 * {scheme} - : -˅---------------------------------------------------^ {path} -˅---------- ----^ -˅ -----------------^ -->
 *               ˅- // -˅ -----------------^- {host} -˅--------------^         ˅- ? - {query} -^  ˅- # - {fragment} -^
 *                      ˅- {userinfo} - @ -^          ˅- : - {port} -^
 */
class ESUri implements Foldable, Associable
{
    use FoldableImp;

    private $uri           = "";
    private $schemeDivider = ":";
    private $pathDelimiter = "/";

    public function __construct($uri, $schemeDivider = ":", $pathDelimiter = "/")
    {
        $this->uri           = $uri;
        $this->schemeDivider = $schemeDivider;
        $this->pathDelimiter = $pathDelimiter;
    }

    public function main(): ESString
    {
        return Shoop::string($this->uri);
    }

    public function args($includeMain = false): array
    {
        $build = [];
        if ($includeMain) {
            $build[] = $this->main()->unfold();
        }

        $build[] = $this->schemeDivider()->unfold();
        $build[] = $thi->pathDelimiter()->unfold();
        return $build;
    }

    private function schemeDivider(): ESString
    {
        return Shoop::string($this->schemeDivider);
    }

    protected function pathDelimiter(): ESString
    {
        return Shoop::string($this->pathDelimiter);
    }

    public function scheme(): ESScheme
    {
        $scheme = $this->asDictionary()->at("scheme")->unfold();
        return Shoop::scheme($scheme);
    }

    public function path(bool $withExtras = true): ESPath
    {
        $path = $this->asDictionary()->at("path")->unfold();
        return Shoop::path($path);
    }

    public function string()
    {
        return Shoop::string($this->main());
    }

// - Maths
    public function plus($value, $at = ""): Addable
    {

    }

    public function minus(
        $items = [" ", "\t", "\n", "\r", "\0", "\x0B"],
        bool $fromStart = true,
        bool $fromEnd   = true
    ): Subtractable
    {

    }

// - Associable
    public function asDictionary(): Associable
    {
        $divider = $this->schemeDivider()->unfold();
        $array   = $this->main()->asArray($divider, false, 2);
        if ($array->asInteger()->is(2)->unfold()) {
            $scheme = $array->first()->unfold();
            $path   = $array->last()->unfold();
            $dictionary = [
                "scheme" => Shoop::scheme($scheme),
                "path"   => Shoop::path($path)
            ];
            return Shoop::dictionary($dictionary);
        }
        return Shoop::dictionary([]);
    }

    public function efToDictionary(): array
    {
        return $this->asDictionary()->unfold();
    }

    public function has($member): Falsifiable
    {

    }

    public function hasAt($member): Falsifiable
    {

    }

    public function offsetExists($offset): bool
    {
        return $this->hasAt()->unfold();
    }

    public function at($member)
    {

    }

    public function offsetGet($offset)
    {
        return $this->at($offset)->unfold();
    }

    public function plusAt(
        $value,
        $member = PHP_INT_MAX,
        bool $overwrite = false
    ): Associable
    {

    }

    public function offsetSet($offset, $value): void
    {

    }

    public function minusAt($member)
    {
    }

    public function offsetUnset($offset): void
    {

    }

    public function rewind(): void
    {

    }

    public function valid(): bool
    {

    }

    public function current()
    {

    }

    public function key()
    {

    }

    public function next(): void
    {

    }
}

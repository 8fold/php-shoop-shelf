<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Foldable\Foldable;
use Eightfold\Foldable\FoldableImp;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\Apply;

use Eightfold\Shoop\FilterContracts\Interfaces\Associable;
use Eightfold\Shoop\FilterContracts\Interfaces\Falsifiable;
use Eightfold\Shoop\FilterContracts\Interfaces\Addable;
use Eightfold\Shoop\FilterContracts\Interfaces\Subtractable;

/**
 * https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#/media/File:URI_syntax_diagram.svg
 *
 * Required
 * scheme - : - path
 *
 * {Optional}
 * scheme - : - // {authority} - path - ? {query} - # {fragment}
 *
 */
class ESUri implements Foldable
{
    use FoldableImp;

    protected $uri               = "";
    protected $schemeDivider     = ":";
    protected $authorityDivider  = "//";
    protected $pathDelimiter     = "/";
    protected $queryDelimiter    = "?";
    protected $fragmentPrefix    = "#";

    public function __construct($uri, $pathDelimiter = "/")
    {
        $this->uri           = $uri;
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

    private function authorityDivider(): ESString
    {
        return Shoop::string($this->authorityDivider);
    }

    private function hasAuthority(): ESBoolean
    {
        $divider = $this->authorityDivider()->unfold();
        $bool    = $this->main()->asArray($divider, true, 2)
            ->asInteger()->is(2)->efToBoolean();
        return Shoop::boolean($bool);
    }

    private function pathDelimiter(): ESString
    {
        return Shoop::string($this->pathDelimiter);
    }

    private function queryDelimiter(): ESString
    {
        return Shoop::string($this->queryDelimiter);
    }

    private function fragmentPrefix(): ESString
    {
        return Shoop::string($this->fragmentPrefix);
    }

    public function scheme(): ESString
    {
        $scheme = Apply::uriScheme()->unfoldUsing($this->main());
        return Shoop::string($scheme);
    }

    public function authority(): ESString
    {
        $authority = Apply::uriAuthority()->unfoldUsing($this->main());
        return Shoop::string($authority);
    }

    public function username(): ESString
    {
        $string = $this->authority()->unfold();

        $array  = Shoop::this($string)->asArray("@", false, 2);
        if ($array->asInteger()->is(2)->efToBoolean()) {
            $array = Shoop::this($array[0])->asArray(":", false, 2);
            if ($array->asInteger()->is(2)->efToBoolean()) {
                return Shoop::string($array[0]);
            }
        }
        return Shoop::string("");
    }

    public function password(): ESString
    {
        $string = $this->authority()->unfold();

        $array  = Shoop::this($string)->asArray("@", false, 2);
        if ($array->asInteger()->is(2)->efToBoolean()) {
            $array = Shoop::this($array[0])->asArray(":", false, 2);
            if ($array->asInteger()->is(2)->efToBoolean()) {
                return Shoop::string($array[1]);
            }
        }
        return Shoop::string("");
    }

    public function host(): ESString
    {
        $string = $this->authority()->unfold();

        $array  = Shoop::this($string)->asArray("@", false, 2);
        if ($array->asInteger()->is(2)->efToBoolean()) {
            $array = Shoop::this($array[1])->asArray(":", false, 2);
            if ($array->asInteger()->is(2)->efToBoolean()) {
                return Shoop::string($array[0]);
            }
        }
        return Shoop::string("");
    }

    public function port(): ESString
    {
        $string = $this->authority()->unfold();

        $array  = Shoop::this($string)->asArray("@", false, 2);
        if ($array->asInteger()->is(2)->efToBoolean()) {
            $array = Shoop::this($array[1])->asArray(":", false, 2);
            if ($array->asInteger()->is(2)->efToBoolean()) {
                return Shoop::string($array[1]);
            }
        }
        return Shoop::string("");
    }

    public function path(): ESString
    {
        $path = Apply::uriPath()->unfoldUsing($this->main());
        return Shoop::string($path);
    }

    public function queryString(): ESString
    {
        $queryString = Apply::uriQuery(true)->unfoldUsing($this->main());
        return Shoop::string($queryString);
    }

    public function query(): ESDictionary
    {
        $queryString = Apply::uriQuery(false, false)->unfoldUsing($this->main());
        return Shoop::dictionary($queryString);
    }

    public function fragmentString(): ESString
    {
        $fragment = Apply::uriFragment(true)->unfoldUsing($this->main());
        return Shoop::string($fragment);
    }

    public function fragment(): ESString
    {
        $fragment = Apply::uriFragment()->unfoldUsing($this->main());
        return Shoop::string($fragment);
    }
}

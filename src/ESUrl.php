<?php

namespace Eightfold\ShoopExtras;

use Eightfold\Shoop\{
    Helpers\Type,
    Interfaces\Shooped,
    Traits\ShoopedImp,
    ESDictionary,
    ESString,
    ESArray,
    ESBool
};

use Eightfold\ShoopExtras\ESPath;
use Eightfold\ShoopExtras\ESUri;
use Eightfold\ShoopExtras\Shoop;

/**
 * https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#/media/File:URI_syntax_diagram.svg
 *
 * {scheme} - : -˅---------------------------------------------------------------------^ {path} -˅---------------^-˅------------------^ -->
 *               ˅- // -˅ -----------------------------------^- {host} -˅--------------^         ˅- ? - {query} -^ ˅- # - {fragment} -^
 *                      ˅- {userinfo} -˅ ---------------^ @ -^          ˅- : - {port} -^
 *                                     ˅- : {password} -^
 */
class ESUrl extends ESUri
{
    static public function processedMain($main)
    {
        return Type::sanitizeType($main, ESString::class)->unfold();
    }

    static public function processedArgs(...$args): array
    {
        $temp = [];

        $temp[] = (isset($args[0]))
            ? Type::sanitizeType($args[0], ESString::class)->unfold()
            : "://";

        $temp[] = (isset($args[1]))
            ? Type::sanitizeType($args[1], ESString::class)->unfold()
            : "/";

        return $temp;
    }

    public function path(bool $withExtras = true): ESPath
    {
        return ($withExtras)
            ? parent::path()
            : parent::path()->string()->divide($this->pathDelimiter(), false, 2)
                ->countIsLessThan(2, function($result, $split) {
                    if ($result->unfold()) { return Shoop::path(""); }

                    $string = $split->last()->divide("?", false, 2)->first()
                        ->start($this->pathDelimiter())->unfold();

                    return Shoop::path($string);
                });
    }

    private function userAndPassword(): ESString
    {
        return $this->path()->string()->divide("@", false, 2)
            ->countIsGreaterThan(2, function($result, $split) {
                return ($result->unfold())
                    ? Shoop::string("")
                    : $split->first();
            });
    }

    public function user(): ESString
    {
        return $this->userAndPassword()->divide(":", false, 2)
            ->countIsGreaterThan(2, function($result, $split) {
                return ($result->unfold())
                    ? Shoop::string("")
                    : $split->first();
            });
    }

    public function password(): ESString
    {
        return $this->userAndPassword()->divide(":", false, 2)
            ->countIsGreaterThan(2, function($result, $split) {
                return ($result->unfold())
                    ? Shoop::string("")
                    : $split->last();
            });
    }

    public function hostAndPort(): ESString
    {
        return $this->path()->string()->divide("@", false, 2)
            ->countIsLessThan(2, function($result, $split) {
                return ($result->unfold())
                    ? Shoop::string("")
                    : $split->last()->divide($this->pathDelimiter, false, 2)
                        ->countIsLessThan(2, function($result, $split) {
                            return ($result->unfold())
                                ? Shoop::string("")
                                : $split->first();
                        });
            });
    }

    public function host(): ESString
    {
        return $this->hostAndPort()->divide(":", false, 2)->first();
    }

    public function port()
    {
        return $this->hostAndPort()->divide(":", false, 2)
            ->countIsLessThan(2, function($result, $split) {
                return ($result->unfold()) ? Shoop::string("") : $split->last();
            });
    }

    public function query(): ESDictionary
    {
        return $this->path()->string()->divide("?", false, 2)
            ->countIsLessThan(2, function($result, $split) {
                if ($result->unfold()) { return Shoop::dictionary([]); }

                // TODO: Make this part of ESArray - alternating value-member pairs, convert to ESDictionary
                //      Should this be the raional default of converting an ESArray to an ESDictionary
                $members = [];
                $values  = [];
                $split->last()->divide("#", false, 2)->first()->divide("&", false)
                    ->each(function($pair) use (&$members, &$values) {
                        list($member, $value) = Shoop::string($pair)->divide("=");
                        $members[] = $member;
                        $values[]  = $value;
                    });
                $combined = array_combine($members, $values);
                return Shoop::dictionary($combined);
            });
    }

    public function fragment()
    {
        return $this->path()->string()->divide("#", false, 2)->countIsLessThan(2, function($result, $split) {
            if ($result->unfold()) { return Shoop::string(""); }
            return $split->last();
        });
    }
}

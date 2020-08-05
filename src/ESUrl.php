<?php

namespace Eightfold\ShoopExtras;

use Eightfold\Shoop\{
    Helpers\Type,
    Interfaces\Shooped,
    Traits\ShoopedImp,
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
    public function __construct($raw, $schemeDivider = "://", $pathDelimiter = "/")
    {
        parent::__construct($raw, $schemeDivider, $pathDelimiter);
    }

    public function path(bool $withExtras = true)
    {
        return ($withExtras)
            ? parent::path()
            : parent::path()->divide($this->pathDelimiter, false, 2)->countIsLessThan(2, function($result, $split) {
                if ($result->unfold()) { return Shoop::string(""); }

                return $split->last()->divide("?", false, 2)->first();
            });
    }

    private function userAndPassword()
    {
        return $this->path()->divide("@", false, 2)->countIsGreaterThan(2, function($result, $split) {
            return ($result->unfold()) ? Shoop::string("") : $split->first();
        });
    }

    public function user()
    {
        return $this->userAndPassword()->divide(":", false, 2)->countIsGreaterThan(2, function($result, $split) {
            return ($result->unfold())
                ? Shoop::string("")
                : $split->first();
        });
    }

    public function password()
    {
        return $this->userAndPassword()->divide(":", false, 2)->countIsGreaterThan(2, function($result, $split) {
            return ($result->unfold())
                ? Shoop::string("")
                : $split->last();
        });
    }

    public function hostAndPort()
    {
        return $this->path()->divide("@", false, 2)->countIsLessThan(2, function($result, $split) {
            return ($result->unfold())
                ? Shoop::string("")
                : $split->last()->divide($this->pathDelimiter, false, 2)->countIsLessThan(2, function($result, $split) {
                    return ($result->unfold())
                        ? Shoop::string("")
                        : $split->first();
                });
        });
    }

    public function host()
    {
        return $this->hostAndPort()->divide(":", false, 2)->first();
    }

    public function port()
    {
        return $this->hostAndPort()->divide(":", false, 2)->countIsLessThan(2, function($result, $split) {
            return ($result->unfold()) ? Shoop::string("") : $split->last();
        });
    }

    public function query()
    {
        return $this->path()->divide("?", false, 2)->countIsLessThan(2, function($result, $split) {
            if ($result->unfold()) { return Shoop::dictionary([]); }

            // TODO: Make this part of ESArray - alternating value-member pairs, convert to ESDictionary
            //      Should this be the raional default of converting an ESArray to an ESDictionary
            $members = [];
            $values = [];
            $split->last()->divide("#", false, 2)->first()->divide("&", false)->each(function($pair) use (&$members, &$values) {
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
        return $this->path()->divide("#", false, 2)->countIsLessThan(2, function($result, $split) {
            if ($result->unfold()) { return Shoop::string(""); }
            return $split->last();
        });
    }
}

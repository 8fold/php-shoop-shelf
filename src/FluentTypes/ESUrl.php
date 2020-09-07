<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Shoop\Shoop;

// use Eightfold\Shoop\FilterContracts\Interfaces\Associable;

// use Eightfold\Shoop\{
//     Helpers\Type,
//     Interfaces\Shooped,
//     Traits\ShoopedImp,
//     ESDictionary,
//     ESString,
//     ESArray,
//     ESBool
// };

// use Eightfold\ShoopShelf\ESPath;
// use Eightfold\ShoopShelf\ESUri;
// use Eightfold\ShoopShelf\Shoop;

/**
 * https://en.wikipedia.org/wiki/Uniform_Resource_Identifier#/media/File:URI_syntax_diagram.svg
 *
 * ESScheme -˅- ESUrlAuthority ----------------------------------------------------^ ESUrlPath - ? - ESUrlQuery - # ESUrlFragment -->
 *           ˅- ESUrlUserInfo - : - ESUrlPassword - @ - ESUrlHost - : - ESUrlPort -^
 */
class ESUrl extends ESUri
{
    private $queryDivider = "?";
    private $fragmentDivider = "#";

    public function __construct($uri)
    {
        $this->uri           = $uri;
        $this->schemeDivider = "://";
    }

    public function path(): ESPath
    {
        $withoutScheme = parent::path()->unfold();
        $parts         = Shoop::this($withoutScheme)
            ->asArray($this->pathDelimiter, false, 2);
        if ($parts->asInteger()->is(2)->unfold()) {
            die(var_dump(
                $parts
            ));
        }

        if ($withExtras) {
            return parent::path();
        }
        die(var_dump(
            parent::path()
        ));
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

    public function userInfo()
    {
        $path = $this->authority()->asArray("@", false, 2);
        die(var_dump($path));
    }

    // private function userAndPassword(): ESString
    // {
    //     return $this->path()->string()->divide("@", false, 2)
    //         ->countIsGreaterThan(2, function($result, $split) {
    //             return ($result->unfold())
    //                 ? Shoop::string("")
    //                 : $split->first();
    //         });
    // }

    // public function user(): ESString
    // {
    //     return $this->userAndPassword()->divide(":", false, 2)
    //         ->countIsGreaterThan(2, function($result, $split) {
    //             return ($result->unfold())
    //                 ? Shoop::string("")
    //                 : $split->first();
    //         });
    // }

    // public function password(): ESString
    // {
    //     return $this->userAndPassword()->divide(":", false, 2)
    //         ->countIsGreaterThan(2, function($result, $split) {
    //             return ($result->unfold())
    //                 ? Shoop::string("")
    //                 : $split->last();
    //         });
    // }

    // public function hostAndPort(): ESString
    // {
    //     return $this->path()->string()->divide("@", false, 2)
    //         ->countIsLessThan(2, function($result, $split) {
    //             return ($result->unfold())
    //                 ? Shoop::string("")
    //                 : $split->last()->divide($this->pathDelimiter, false, 2)
    //                     ->countIsLessThan(2, function($result, $split) {
    //                         return ($result->unfold())
    //                             ? Shoop::string("")
    //                             : $split->first();
    //                     });
    //         });
    // }

    // public function host(): ESString
    // {
    //     return $this->hostAndPort()->divide(":", false, 2)->first();
    // }

    // public function port()
    // {
    //     return $this->hostAndPort()->divide(":", false, 2)
    //         ->countIsLessThan(2, function($result, $split) {
    //             return ($result->unfold()) ? Shoop::string("") : $split->last();
    //         });
    // }

    // public function query(): ESDictionary
    // {
    //     return $this->path()->string()->divide("?", false, 2)
    //         ->countIsLessThan(2, function($result, $split) {
    //             if ($result->unfold()) { return Shoop::dictionary([]); }

    //             // TODO: Make this part of ESArray - alternating value-member pairs, convert to ESDictionary
    //             //      Should this be the raional default of converting an ESArray to an ESDictionary
    //             $members = [];
    //             $values  = [];
    //             $split->last()->divide("#", false, 2)->first()->divide("&", false)
    //                 ->each(function($pair) use (&$members, &$values) {
    //                     list($member, $value) = Shoop::string($pair)->divide("=");
    //                     $members[] = $member;
    //                     $values[]  = $value;
    //                 });
    //             $combined = array_combine($members, $values);
    //             return Shoop::dictionary($combined);
    //         });
    // }

    // public function fragment()
    // {
    //     return $this->path()->string()->divide("#", false, 2)->countIsLessThan(2, function($result, $split) {
    //         if ($result->unfold()) { return Shoop::string(""); }
    //         return $split->last();
    //     });
    // }

// - Associable
    // public function asDictionary(): Associable
    // {
    //     $dictionary = parent::asDictionary();


    //     $divider = $this->schemeDivider()->unfold();
    //     $array   = $this->main()->asArray($divider, false, 2);
    //     if ($array->asInteger()->is(2)->unfold()) {
    //         $scheme = $array->first()->unfold();
    //         $path   = $array->last()->unfold();
    //         $dictionary = [
    //             "scheme" => Shoop::scheme($scheme),
    //             "path"   => Shoop::path($path),
    //         ];
    //         return Shoop::dictionary($dictionary);
    //     }
    //     return Shoop::dictionary([]);
    // }
}

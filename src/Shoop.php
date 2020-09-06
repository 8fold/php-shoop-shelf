<?php

namespace Eightfold\ShoopShelf;

use Eightfold\Shoop\Shoop as EFShoop;

use Eightfold\ShoopShelf\FluentTypes\ESMarkdown;
use Eightfold\ShoopShelf\FluentTypes\ESStore;
use Eightfold\ShoopShelf\FluentTypes\ESPath;
use Eightfold\ShoopShelf\FluentTypes\ESUri;
use Eightfold\ShoopShelf\FluentTypes\ESScheme;
use Eightfold\ShoopShelf\FluentTypes\ESUrl;
use Eightfold\ShoopShelf\FluentTypes\ESArray;
use Eightfold\ShoopShelf\FluentTypes\ESDictionary;
use Eightfold\ShoopShelf\FluentTypes\ESString;
use Eightfold\ShoopShelf\FluentTypes\ESBoolean;

class Shoop extends EFShoop
{
    static public function markdown($content, ...$extensions): ESMarkdown
    {
        return ESMarkdown::fold($content, ...$extensions);
    }

    static public function store($path): ESStore
    {
        return ESStore::fold($path);
    }

    static public function path($path): ESPath
    {
        return ESPath::fold($path);
    }

    static public function uri($uri): ESUri
    {
        return ESUri::fold($uri);
    }

    static public function scheme($scheme): ESScheme
    {
        return ESScheme::fold($scheme);
    }

    static public function url($url): ESUrl
    {
        return ESUrl::fold($url);
    }

    static public function array($array): ESArray
    {
        return ESArray::fold($array);
    }

    static public function dictionary($dictionary): ESDictionary
    {
        return ESDictionary::fold($dictionary);
    }

    static public function string($string): ESString
    {
        return ESString::fold($string);
    }

    static public function boolean($boolean): ESBoolean
    {
        return ESBoolean::fold($boolean);
    }
}

<?php

namespace Eightfold\ShoopExtras;

use Eightfold\Shoop\Helpers\Type;
use Eightfold\Shoop\Interfaces\Shooped;
use Eightfold\Shoop\Traits\ShoopedImp;
use Eightfold\Shoop\{
    ESString,
    ESArray,
    ESBool
};

use Eightfold\ShoopExtras\Shoop;

class ESStore implements Shooped
{
    use ShoopedImp;

    public function __construct($path)
    {
        $this->value = Type::sanitizeType($path, ESString::class)->unfold();
    }

    public function parent($length = 1)
    {
        return $this->dropLast()->isFolder(function($result, $path) {
            return ($result)
                ? Shoop::store($path)
                : Shoop::store($path)->parent();
        });
    }

    public function string(): ESString
    {
        return Shoop::string($this->value());
    }

    private function parts()
    {
        return $this->string()->divide("/");
    }

    public function array(): ESArray
    {
        return $this->parts()->noEmpties()->reindex();
    }

    public function plus(...$parts)
    {
        $path = $this->parts()->plus(...$parts)->join("/");
        return Shoop::store($path);
    }

    public function dropLast($length = 1)
    {
        $path = $this->parts()->dropLast($length)->join("/");
        return Shoop::store($path);
    }

    public function noEmpties()
    {
        $path = $this->parts()->noEmpties()->join("/");
        return Shoop::string($path);
    }

    public function markdown()
    {
        return ($this->isFile)
            ? Shoop::markdown($this->content)
            : Shoop::markdown("");
    }

    public function isFolder(\Closure $closure = null)
    {
        $value = $this->value();
        $bool = is_dir($value);
        if ($closure === null) {
            $closure = function($bool) {
                return Shoop::this($bool);
            };
        }
        return $closure($bool, Shoop::this($this));
    }

    public function isFile(\Closure $closure = null)
    {
        $value = $this->value();
        $bool = is_file($value);
        if ($closure === null) {
            $closure = function($bool) {
                return Shoop::this($bool);
            };
        }
        return $closure($bool, Shoop::this($this));
    }

    public function content($trim = true)
    {
        $trim = Type::sanitizeType($trim, ESBool::class)->unfold();
        $path = $this->value();
        if (file_exists($path) and is_file($path)) {
            $contents = file_get_contents($path);
            if (strlen($contents) > 0) {
                return ($trim)
                    ? Shoop::string($contents)->trim()
                    : Shoop::string($contents);
            }

        } elseif (is_dir($path)) {
            return Shoop::array(scandir($path))->each(function($item) use ($path, $trim) {
                $bool = Shoop::array([".", "..", ".DS_Store"])->hasUnfolded($item);
                return ($trim and $bool)
                    ? Shoop::string("")
                    : ESStore::fold($path ."/{$item}");

            })->noEmpties()->reindex();

        }
        return Shoop::string("");
    }

    public function folders()
    {
        return ($this->isFile)
            ? Shoop::array([])
            : $this->content()->each(function($path) {
            $path = ESStore::fold($path);
            return ($path->isFile) ? Shoop::string("") : $path;
        })->noEmpties()->reindex();
    }

    public function files()
    {
        return ($this->isFile)
            ? Shoop::array([])
            : $this->content()->each(function($path) {
            $path = ESStore::fold($path);
            return ($path->isFolder) ? Shoop::string("") : $path;
        })->noEmpties()->reindex();
    }
}

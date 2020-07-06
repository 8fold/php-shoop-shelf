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

class ESStore extends ESPath
{
    public function markdown()
    {
        return ($this->isFile)
            ? Shoop::markdown($this->content)
            : Shoop::markdown("");
    }

    public function metaMember($memberName)
    {
        $value = $this->markdown()->meta()->{$memberName};
        if ($value === null) {
            return Shoop::string("");
        }
        return Shoop::this($value);
    }

    public function isFolder(\Closure $closure = null)
    {
        $value = $this->value();
        $bool = is_dir($value);
        return $this->condition($bool, $closure);
    }

    public function isFile(\Closure $closure = null)
    {
        $value = $this->value();
        $bool = is_file($value);
        return $this->condition($bool, $closure);
    }

    public function content(
        $trim = true,
        $ignore = [".", "..", ".DS_Store", ".gitignore"]
    )
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
            return Shoop::array(scandir($path))->each(
                function($item) use ($path, $trim, $ignore) {
                    $bool = Shoop::array($ignore)->hasUnfolded($item);
                    return ($trim and $bool)
                        ? Shoop::string("")
                        : Shoop::string($path ."/{$item}");

            })->noEmpties()->reindex();

        }
        return Shoop::string("");
    }

    public function folders()
    {
        return ($this->isFile)
            ? Shoop::array([])
            : $this->content()->each(function($path) use ($endsWith) {
                $store = Shoop::store($path);
                return ($store->isFolder)
                    ? $store
                    : Shoop::string("");
            })->noEmpties()->reindex();
    }

    public function files($endsWith = "*")
    {
        return ($this->isFile)
            ? Shoop::array([])
            : $this->content()->each(function($path) use ($endsWith) {
                $store = Shoop::store($path);
                return $store->isFile(function($result, $store) use ($endsWith) {
                    // TODO: One would think this could be simplified unless check is paramount
                    if (! $result->unfold()) {
                        return Shoop::string("");

                    } elseif (Shoop::string($endsWith)->isUnfolded("*")) {
                        return $store;

                    } elseif ($store->string()->endsWithUnfolded($endsWith)) {
                        return $store;

                    }
                    return Shoop::string("");
                });
        })->noEmpties()->reindex();
    }
}

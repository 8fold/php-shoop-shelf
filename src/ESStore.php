<?php

namespace Eightfold\ShoopExtras;

use \Closure;

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
    public function markdown(...$extensions)
    {
        return ($this->isFile)
            ? Shoop::markdown($this->content, ...$extensions)
            : Shoop::markdown("", ...$extensions);
    }

    public function metaMember($memberName)
    {
        $value = $this->markdown()->meta()->{$memberName};
        if ($value === null) {
            return Shoop::string("");
        }
        return Shoop::this($value);
    }

    // TODO: Use the one from ShoopedImp somehow
    public function condition($bool, Closure $closure = null)
    {
        $bool = Type::sanitizeType($bool, ESBool::class);
        $value = $this->value();
        if ($closure === null) {
            $closure = function($bool, $value) {
                return $bool;
            };
        }
        return $closure($bool, Shoop::store($value));
    }

    public function isFolder(Closure $closure = null)
    {
        $value = $this->value();
        $bool = is_dir($value);
        return $this->condition($bool, $closure);
    }

    public function isNotFolder(Closure $closure = null)
    {
        $bool = $this->isFolder()->toggle;
        return $this->condition($bool, $closure);
    }

    public function isFile(Closure $closure = null)
    {
        $value = $this->value();
        $bool = is_file($value);
        return $this->condition($bool, $closure);
    }

    public function isNotFile(Closure $closure = null)
    {
        $bool = $this->isFile()->toggle;
        return $this->condition($bool, $closure);
    }

    public function content($trim = true, $ignore = [".", "..", ".DS_Store"])
    {
        $trim = Type::sanitizeType($trim, ESBool::class);
        $ignore = Type::sanitizeType($ignore, ESArray::class);

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

    public function files($trim = true, $ignore = [".", "..", ".DS_Store"], $endsWith = "*")
    {
        $trim = Type::sanitizeType($trim, ESBool::class);
        $ignore = Type::sanitizeType($ignore, ESArray::class);
        $endsWith = Type::sanitizeType($endsWith, ESString::class);
        return ($this->isFile)
            ? Shoop::array([])
            : $this->content(true, $ignore)->each(function($path) use ($ignore, $endsWith) {
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

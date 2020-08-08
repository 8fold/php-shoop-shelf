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
    const PREPEND = "prepend";

    const APPEND = "append";

    const OVERWRITE = "overwrite";

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

    public function endsWith($needle, Closure $closure = null)
    {
        $needle = Type::sanitizeType($needle, ESString::class);
        $bool = Shoop::string($this->main())->endsWith($needle);
        return $this->condition($bool, $closure);
    }

    public function isFolder(Closure $closure = null)
    {
        $value = $this->main();
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
        $value = $this->main();
        $bool = is_file($value);
        return $this->condition($bool, $closure);
    }

    public function isNotFile(Closure $closure = null)
    {
        $bool = $this->isFile()->toggle;
        return $this->condition($bool, $closure);
    }

    public function content(
        $trim = true,
        $ignore = [".", "..", ".DS_Store"]
    ) // PHP 8.0 ESString|ESArray
    {
        $trim   = Type::sanitizeType($trim, ESBool::class);
        $ignore = Type::sanitizeType($ignore, ESArray::class);

        $path = $this->main();
        if (file_exists($path) and is_file($path)) {
            $contents = file_get_contents($path);
            if ($trim->unfold()) {
                return Shoop::string($contents)->trim();
            }
            return Shoop::string($contents);

        } elseif (is_dir($path)) {
            $contents = Shoop::array(scandir($path));
            if ($trim->unfold()) {
                $contents = $contents->filter(function($value) use ($ignore) {
                    return ! in_array($value, $ignore->unfold());
                });

                if ($contents->isEmpty) {
                    return Shoop::array([]);
                }
            }

            return $contents->each(function($item) use ($path) {
                    return Shoop::string($path ."/{$item}");
            })->noEmpties()->reindex();

        }
        return Shoop::string("");
    }

    public function saveContent(
        $content,
        $placement = ESStore::OVERWRITE,
        $makeFolder = true
    )
    {
        $content    = Type::sanitizeType($content, ESString::class);
        $makeFolder = Type::sanitizeType($makeFolder, ESBool::class);

        if ($this->isNotFile() and $makeFolder->unfold()) {
            $this->dropLast()->makeFolder();

        }

        if ($placement === ESStore::PREPEND) {
            $content = $this->content()->start($content);

        } elseif ($placement === ESStore::APPEND) {
            $content = $this->content()->plus($content);

        }
        $bytesOrFalse = file_put_contents($this->unfold(), $content);
        return $this;
    }

    private function makeFolder()
    {
        @mkdir($this->unfold(), 0755, true);
    }

    public function folders(): ESArray
    {
        return ($this->isFile)
            ? Shoop::array([])
            : $this->content()->each(function($path) {
                $store = Shoop::store($path);
                return ($store->isFolder)
                    ? $store->unfold()
                    : Shoop::string("");
            })->noEmpties()->reindex();
    }

    public function files(
        $trim = true,
        $ignore = [".", "..", ".DS_Store"],
        $endsWith = "*"
    ): ESArray
    {
        $trim     = Type::sanitizeType($trim, ESBool::class);
        $ignore   = Type::sanitizeType($ignore, ESArray::class);
        $endsWith = Type::sanitizeType($endsWith, ESString::class);
        return ($this->isFile)
            ? Shoop::array([])
            : $this->content(true, $ignore)
                ->each(function($path) use ($ignore, $endsWith) {
                    $store = Shoop::store($path);
                    return $store->isFile(
                        function($result, $store) use ($endsWith) {
                            // TODO: One would think this could be simplified unless check is paramount
                            if (! $result->unfold()) {
                                return Shoop::string("");

                            } elseif (Shoop::string($endsWith)->isUnfolded("*")) {
                                return $store->unfold();

                            } elseif ($store->string()->endsWithUnfolded($endsWith)) {
                                return $store->unfold();

                            }
                            return Shoop::string("");
                        });
                })->noEmpties()->reindex();
    }
}

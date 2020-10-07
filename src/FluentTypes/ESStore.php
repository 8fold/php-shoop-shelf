<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Foldable\Fold;

use Eightfold\Shoop\Shooped;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\Apply;

class ESStore extends Fold
{
    public function main()
    {
        return Shoop::this($this->main);
    }

    public function append(array $parts): ESStore
    {
        return static::fold(
            $this->parts()->append($parts)->efToString("/")
        );
    }

    public function exists(): Shooped
    {
        $path = $this->main()->unfold();
        return Shoop::this(
            file_exists($path)
        );
    }

    public function isFile(): Shooped
    {
        return Shoop::this(
            is_file($this->main()->unfold())
        );
    }

    public function isFolder(): Shooped
    {
        return Shoop::this(
            is_dir($this->main()->unfold())
        );
    }

    public function up(int $levels = 1)
    {
        return static::fold(
            $this->parts()->dropLast($levels)->efToString("/")
        );
    }

    public function parts(): Shooped
    {
        return $this->main()->divide("/");
    }

    public function folders()
    {
        return $this->content(false);
    }

    public function files()
    {
        return $this->content(true, false);
    }

    public function markdown(...$extensions)
    {
        $content = $this->content()->unfold();
        return Shoop::markdown($content, ...$extensions);
    }

    public function content(
        bool $includeFiles = true,
        bool $includeFolders = true,
        array $ignore = [".", "..", ".DS_Store"]
    )
    {
        if ($this->isFile()->unfold()) {
            return Shoop::this(
                file_get_contents($this->main()->unfold())
            );
        }

        $content = scandir($this->main()->unfold());
        $path    = $this->main()->unfold();
        return Shoop::this($content)->each(function($v, $m, &$build) use
            ($path, $includeFiles, $includeFolders, $ignore) {
                if (Shoop::this($v)->isEmpty()->reversed()->unfold() and
                    Shoop::this($ignore)->has($v)->reversed()->unfold()
                ) {
                    $path = $path ."/". $v;
                    if ($includeFiles and static::fold($path)->isFile()->unfold()) {
                        $build[] = $path;

                    } elseif ($includeFolders and static::fold($path)->isFolder()->unfold()) {
                        $build[] = $path;

                    }
                }
            }
        );
    }

    public function saveContent(
        $content,
        $makeFolder = true
    )
    {
        $main = $this->main()->unfold();
        Apply::saveContentToFile($content, $main, $makeFolder)
            ->unfoldUsing($main);
        return static::fold($main);
    }

    public function delete()
    {
        if ($this->exists()->reversed()->efToBoolean()) {
            return;
        }

        $path = $this->main()->unfold();
        if ($this->isFile()->unfold()) {
            unlink($path);

        } elseif ($this->isFolder()->unfold()) {
            $this->content()->each(function($path) {
                static::fold($path)->delete();
            });
            rmdir($this->main()->unfold());

        }
    }
}

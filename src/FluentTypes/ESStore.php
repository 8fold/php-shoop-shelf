<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Foldable\Fold;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

use Eightfold\Shoop\Shooped;

use Eightfold\ShoopShelf\Shoop;

class ESStore extends Fold
{
    private $path = "";

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function path()
    {
        return Shoop::this($this->path);
    }

    /**
     * @deprecated
     */
    public function main()
    {
        return $this->path();
    }

    public function parts(): Shooped
    {
        return $this->main()->divide("/");
    }

    public function up(int $levels = 1)
    {
        return static::fold(
            $this->parts()->dropLast($levels)->efToString("/")
        );
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
            is_file($this->path()->unfold())
        );
    }

    public function isFolder(): Shooped
    {
        return Shoop::this(
            is_dir($this->path()->unfold())
        );
    }

    public function markdown(...$extensions)
    {
        $content = $this->content()->unfold();
        return Shoop::markdown($content, ...$extensions);
    }

    public function unfold()
    {
        return $this->main()->unfold();
    }

    public function folders()
    {
        return $this->content(false);
    }

    public function files()
    {
        return $this->content(true, false);
    }

    public function content(
        bool $includeFiles = true,
        bool $includeFolders = true,
        array $ignore = [".", "..", ".DS_Store"] // no longer used
    )
    {
        $adapter    = new LocalFilesystemAdapter("/");
        $fileSystem = new Filesystem($adapter);
        if ($this->isFile()->unfold()) {
            $content = $fileSystem->read($this->path()->unfold());
            return Shoop::this(
                trim($content)
            );
        }

        $content = $fileSystem->listContents($this->path()->unfold());
        if ($includeFiles and ! $includeFolders) {
            $content = $content->filter(
                fn(StorageAttributes $attributes) => $attributes->isFile()
            );

        } elseif (! $includeFiles and $includeFolders) {
            $content = $content->filter(
                fn(StorageAttributes $attributes) => $attributes->isDir()
            );
        }

        return Shoop::this(
            $content->map(
                fn(StorageAttributes $attributes) => "/". $attributes->path()
            )->toArray()
        )->asArray();
    }

    public function saveContent(
        $content,
        $makeFolder = true // no longer used
    )
    {
        $adapter    = new LocalFilesystemAdapter("/");
        $fileSystem = new Filesystem($adapter);
        $fileSystem->write(
            $this->path()->unfold(),
            $content
        );

        return static::fold(
            $this->path()->unfold()
        );
    }

    public function delete()
    {
        if ($this->exists()->reversed()->efToBoolean()) {
            return;
        }

        $adapter    = new LocalFilesystemAdapter("/");
        $fileSystem = new Filesystem($adapter);
        if ($this->isFile()->unfold()) {
            $fileSystem->delete(
                $this->path()->unfold()
            );

        } elseif ($this->isFolder()->unfold()) {
            $fileSystem->deleteDirectory(
                $this->path()->unfold()
            );

        }

        return static::fold(
            $this->path()->unfold()
        );
    }
}

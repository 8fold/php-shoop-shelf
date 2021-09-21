<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Foldable\Fold;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;

// Flysystem 2.0
// use League\Flysystem\Local\LocalFilesystemAdapter;
// use League\Flysystem\Filesystem;
// use League\Flysystem\StorageAttributes;

use Eightfold\Shoop\Shooped;

use Eightfold\ShoopShelf\Shoop;

class ESStore extends Fold
{
    private $path = "";

    private $adapter = null;

    private $fileSystem = null;

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

    public function mimeType(): Shooped
    {
        return Shoop::this(
            mime_content_type($this->main()->unfold())
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

    private function adapter()
    {
        if ($this->adapter == null) {
            if (class_exists(\League\Flysystem\Local\LocalFilesystemAdapter::class)) {
                $this->adapter = new \League\Flysystem\Local\LocalFilesystemAdapter("/");

            } else {
                $this->adapter = new Local("/");

            }
        }
        return $this->adapter;
    }

    private function fileSystem()
    {
        if ($this->fileSystem == null) {
            if (class_exists(\League\Flysystem\Local\LocalFilesystemAdapter::class)) {
                $this->fileSystem = new \League\Flysystem\Filesystem($this->adapter());

            } else {
                $this->fileSystem = new Filesystem($this->adapter());

            }
        }
        return $this->fileSystem;
    }

    private function contentList()
    {
        return $this->fileSystem()->listContents($this->path()->unfold());
    }

    private function foldersList(): array
    {
        if (class_exists(\League\Flysystem\Local\LocalFilesystemAdapter::class)) {
            $base = $this->contentList()->filter(
                fn(\League\Flysystem\StorageAttributes $attributes) => $attributes->isDir()

            )->map(
                fn(\League\Flysystem\StorageAttributes $attributes) => "/". $attributes->path()

            )->sortByPath()->toArray();

            return $base;
        }

        $content = $this->contentList();

        $folders = Shoop::this($content)->retain(function($v) {
            $path = "/". $v["path"];
            $store = Shoop::store($path);
            return $store->isFolder()->unfold();
        });

        return ($folders->isEmpty()->efToBoolean())
            ? []
            : $folders->each(fn($v) => "/". $v["path"])->efToArray();

    }

    private function filesList(): array
    {
        if (class_exists(\League\Flysystem\Local\LocalFilesystemAdapter::class)) {
            $base = $this->fileSystem()->listContents($this->path()->unfold())->filter(
                fn(\League\Flysystem\StorageAttributes $attributes) => $attributes->isFile()

            )->sortByPath()->map(
                fn(\League\Flysystem\StorageAttributes $attributes) => "/". $attributes->path()

            )->toArray();

            return $base;
        }

        $content = $this->fileSystem()->listContents($this->path()->unfold());
// "league/flysystem": "^1.1.5"
        $files = Shoop::this($content)->retain(function($v) {
            $path = "/". $v["path"];
            $store = Shoop::store($path);
            return $store->isFile()->unfold();
        });

        return ($files->isEmpty()->efToBoolean())
            ? []
            : $files->each(fn($v) => "/". $v["path"])->efToArray();

    }

    public function content(
        bool $includeFiles = true,
        bool $includeFolders = true,
        array $ignore = [".", "..", ".DS_Store"] // no longer used
    )
    {

        if ($this->isFile()->efToBoolean()) {
            $content = $this->fileSystem()->read($this->path()->unfold());
            return Shoop::this($content);

        }

        $folders = ($includeFolders)
            ? $folders = $this->foldersList()
            : [];

        $files = ($includeFiles)
            ? $this->filesList()
            : [];

        return Shoop::this($folders)->append($files)->asArray();
    }

    public function saveContent(
        $content,
        $makeFolder = true // no longer used
    )
    {
        $this->fileSystem()->write(
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

        if ($this->isFile()->unfold()) {
            $this->fileSystem()->delete(
                $this->path()->unfold()
            );

        } elseif ($this->isFolder()->unfold()) {
            if (class_exists(\League\Flysystem\Local\LocalFilesystemAdapter::class)) {
                $this->fileSystem()->deleteDirectory(
                    $this->path()->unfold()
                );

            } else {
                $this->fileSystem()->deleteDir(
                    $this->path()->unfold()
                );

            }
        }

        return static::fold(
            $this->path()->unfold()
        );
    }
}

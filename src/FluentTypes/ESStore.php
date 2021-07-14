<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Foldable\Fold;

// use League\Flysystem\Filesystem;
// use League\Flysystem\Adapter\Local;

// Flysystem 2.0
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

    public function content(
        bool $includeFiles = true,
        bool $includeFolders = true,
        array $ignore = [".", "..", ".DS_Store"] // no longer used
    )
    {
        // $adapter    = new Local("/");
        // $fileSystem = new Filesystem($adapter);
        $adapter    = new LocalFilesystemAdapter("/");
        $fileSystem = new Filesystem($adapter);
        if ($this->isFile()->unfold()) {
            $content = $fileSystem->read($this->path()->unfold());
            return Shoop::this($content);

        }

        // $content = $fileSystem->listContents($this->path()->unfold());

        $folders = [];
        if ($includeFolders) {
            $folders = $fileSystem->listContents($this->path()->unfold())->filter(
                fn(StorageAttributes $attributes) => $attributes->isDir()
            )->map(
                fn(StorageAttributes $attributes) => "/". $attributes->path()
            )->toArray();

            $folders = Shoop::this($folders)->sort(SORT_NATURAL)->efToArray();

        }

        $files   = [];
        if ($includeFiles) {
            $files = $fileSystem->listContents($this->path()->unfold())->filter(
                fn(StorageAttributes $attributes) => $attributes->isFile()
            )->map(
                fn(StorageAttributes $attributes) => "/". $attributes->path()
            )->toArray();

            $files = Shoop::this($files)->sort(SORT_NATURAL)->efToArray();

        }

        return Shoop::this($folders)->append($files)->asArray();
    }

    public function saveContent(
        $content,
        $makeFolder = true // no longer used
    )
    {
        // $adapter    = new Local("/");
        // $fileSystem = new Filesystem($adapter);
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

        // $adapter    = new Local("/");
        // $fileSystem = new Filesystem($adapter);
        $adapter    = new LocalFilesystemAdapter("/");
        $fileSystem = new Filesystem($adapter);
        if ($this->isFile()->unfold()) {
            $fileSystem->delete(
                $this->path()->unfold()
            );

        } elseif ($this->isFolder()->unfold()) {
            // $fileSystem->deleteDir(
            //     $this->path()->unfold()
            // );
            // FlySystem 2.0
            $fileSystem->deleteDirectory(
                $this->path()->unfold()
            );

        }

        return static::fold(
            $this->path()->unfold()
        );
    }
}

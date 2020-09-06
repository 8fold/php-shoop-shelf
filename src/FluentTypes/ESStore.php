<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\ShoopShelf\Apply;

use Eightfold\ShoopShelf\Shoop;

class ESStore extends ESPath
{
    // public function metaMember($memberName)
    // {
    //     $value = $this->markdown()->meta()->{$memberName};
    //     if ($value === null) {
    //         return Shoop::string("");
    //     }
    //     return Shoop::this($value);
    // }

    // public function endsWith($needle, Closure $closure = null)
    // {
    //     $needle = Type::sanitizeType($needle, ESString::class);
    //     $bool = Shoop::string($this->main())->endsWith($needle);
    //     return $this->condition($bool, $closure);
    // }

    public function minusLast($length = 1)
    {
        $path = $this->main()->divide("/")->minusLast($length)->join("/")->unfold();
        return static::fold($path);
    }

    public function isFolder(): ESBoolean
    {
        $bool = Apply::isFolder()->unfoldUsing($this->main());
        return Shoop::boolean($bool);
    }

    public function isFile(): ESBoolean
    {
        $bool = Apply::isFile()->unfoldUsing($this->main());
        return Shoop::boolean($bool);
    }

    public function markdown(): ESMarkdown
    {
        $content = $this->content()->unfold();
        return ESMarkdown::fold($content);
    }

    public function content(
        bool $includeFiles = true,
        bool $includeFolders = true,
        array $ignore = [".", "..", ".DS_Store"]
    ) // PHP 8.0 ESString|ESArray
    {
        $path = $this->main();
        if (Apply::isFile()->unfoldUsing($path)) {
            $content = Apply::fileContent()->unfoldUsing($path);
            return Shoop::string($content);

        } elseif (Apply::isFolder()->unfoldUsing($path)) {
            $content = Apply::FolderContent(
                $includeFiles,
                $includeFolders,
                $ignore
            )->unfoldUsing($path);
            return Shoop::array($content);

        }
        return Shoop::string("");
    }

    public function folders(
        $ignore = [".", "..", ".DS_Store"]
    ): ESArray
    {
        if ($this->isFile()->unfold()) {
            return Shoop::array([]);
        }

        // $main = $this->main();
        $content = $this->content(false, true, $ignore);// Apply::folderContent(false)->unfoldUsing($main);

        return Shoop::array($content);
    }

    public function files(
        $ignore = [".", "..", ".DS_Store"],
        $endsWith = "*"
    ): ESArray
    {
        if ($this->isFile()->unfold()) {
            return Shoop::array([]);
        }

        // $main = $this->main();
        $content = $this->content(true, false, $ignore); // Apply::folderContent(true, false, $ignore)->unfoldUsing($main);

        return Shoop::array($content);

        $content = $this->content(true, $ignore);

        // TODO: Use filters
        $build = [];
        foreach ($content as $path) {
            $store = Shoop::store($path);
            if ($store->isFile()->unfold()) {
                if (Apply::is("*")->unfoldUsing($endsWith) or
                    Apply::endsWith($endsWith)->unfoldUsing($path)
                ) {
                    $build[] = $store->unfold();

                }
            }
        }
        $build = array_filter($build);
        $build = array_values($build);

        return Shoop::array($build);
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
        $main = $this->main()->unfold();
        if (Apply::isFile()->unfoldUsing($main)) {
            Apply::deleteFile()->unfoldUsing($main);

        } elseif (Apply::isFolder()->unfoldUsing($main)) {
            Apply::deleteFolder()->unfoldUsing($main);

        }

        return static::fold($main);
    }
}

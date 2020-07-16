<?php

namespace Eightfold\ShoopExtras;

use \Closure;

use Github\Client;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;

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

class ESGitHubClient extends ESPath
{
    private $ghToken;
    private $ghUsername;
    private $ghRepo;

    private $useCache     = false;
    private $cacheRootPath = "";

    private $client = null;

    public function __construct($main, ...$args)
    {
        parent::__construct($main);
        $this->ghToken    = $args[0];
        $this->ghUsername = $args[1];
        $this->ghRepo     = $args[2];
    }

    public function plus(...$parts)
    {
        $path = $this->parts()->plus(...$parts)->join("/")->start("/");
        return static::fold($path, $this->ghToken, $this->ghUsername, $this->ghRepo);
    }

    public function client()
    {
        if ($this->client === null) {
            $this->client = new Client();

            if ($this->useCache) {
                $adapter = new Local($this->cacheRootPath);
                $filesystem = new Filesystem($adapter);
                $pool = new FilesystemCachePool($filesystem);
                $this->client->addCache($pool);
            }

            $this->client->authenticate($this->ghToken, null, Client::AUTH_ACCESS_TOKEN);
        }
        return $this->client;
    }

    public function cache(string $cacheRootPath = "")
    {
        $this->cacheRootPath = Shoop::string($cacheRootPath)->countIsGreaterThan(0,
            function($result, $cacheRootPath) {
                if ($result->unfold()) {
                    $this->client = null;
                    $this->useCache = true;
                    return $cacheRootPath;
                }
                return "";
            });
        return $this;
    }

    public function exists()
    {
        $bool = $this->client()->api("repo")->contents()->exists(
            $this->ghUsername,
            $this->ghRepo,
            $this->value()
        );
        return Shoop::bool($bool);
    }

    public function markdown(...$extensions)
    {
        if ($this->exists()) {
            $content = $this->client()->api("repo")->contents()->download(
                $this->ghUsername,
                $this->ghRepo,
                $this->value()
            );
            $content = Shoop::string($content)->trim();
            return Shoop::markdown($content, ...$extensions);
        }
        return Shoop::markdown("", ...$extensions);
    }

    // public function metaMember($memberName)
    // {
    //     $value = $this->markdown()->meta()->{$memberName};
    //     if ($value === null) {
    //         return Shoop::string("");
    //     }
    //     return Shoop::this($value);
    // }

    // // TODO: Use the one from ShoopedImp somehow
    // public function condition($bool, Closure $closure = null)
    // {
    //     $bool = Type::sanitizeType($bool, ESBool::class);
    //     $value = $this->value();
    //     if ($closure === null) {
    //         $closure = function($bool, $value) {
    //             return $bool;
    //         };
    //     }
    //     return $closure($bool, Shoop::store($value));
    // }

    // public function endsWith($needle, Closure $closure = null)
    // {
    //     $needle = Type::sanitizeType($needle, ESString::class);
    //     $bool = Shoop::string($this->value())->endsWith($needle);
    //     return $this->condition($bool, $closure);
    // }

    // public function isFolder(Closure $closure = null)
    // {
    //     $value = $this->value();
    //     $bool = is_dir($value);
    //     return $this->condition($bool, $closure);
    // }

    // public function isNotFolder(Closure $closure = null)
    // {
    //     $bool = $this->isFolder()->toggle;
    //     return $this->condition($bool, $closure);
    // }

    // public function isFile(Closure $closure = null)
    // {
    //     $value = $this->value();
    //     $bool = is_file($value);
    //     return $this->condition($bool, $closure);
    // }

    // public function isNotFile(Closure $closure = null)
    // {
    //     $bool = $this->isFile()->toggle;
    //     return $this->condition($bool, $closure);
    // }

    // public function content($trim = true, $ignore = [".", "..", ".DS_Store"])
    // {
    //     $trim = Type::sanitizeType($trim, ESBool::class);
    //     $ignore = Type::sanitizeType($ignore, ESArray::class);

    //     $path = $this->value();
    //     if (file_exists($path) and is_file($path)) {
    //         $contents = file_get_contents($path);
    //         if (strlen($contents) > 0) {
    //             return ($trim)
    //                 ? Shoop::string($contents)->trim()
    //                 : Shoop::string($contents);
    //         }

    //     } elseif (is_dir($path)) {
    //         return Shoop::array(scandir($path))->each(
    //             function($item) use ($path, $trim, $ignore) {
    //                 $bool = Shoop::array($ignore)->hasUnfolded($item);
    //                 return ($trim and $bool)
    //                     ? Shoop::string("")
    //                     : Shoop::string($path ."/{$item}");

    //         })->noEmpties()->reindex();

    //     }
    //     return Shoop::string("");
    // }

    // public function folders()
    // {
    //     return ($this->isFile)
    //         ? Shoop::array([])
    //         : $this->content()->each(function($path) use ($endsWith) {
    //             $store = Shoop::store($path);
    //             return ($store->isFolder)
    //                 ? $store
    //                 : Shoop::string("");
    //         })->noEmpties()->reindex();
    // }

    // public function files($trim = true, $ignore = [".", "..", ".DS_Store"], $endsWith = "*")
    // {
    //     $trim = Type::sanitizeType($trim, ESBool::class);
    //     $ignore = Type::sanitizeType($ignore, ESArray::class);
    //     $endsWith = Type::sanitizeType($endsWith, ESString::class);
    //     return ($this->isFile)
    //         ? Shoop::array([])
    //         : $this->content(true, $ignore)->each(function($path) use ($ignore, $endsWith) {
    //             $store = Shoop::store($path);
    //             return $store->isFile(function($result, $store) use ($endsWith) {
    //                 // TODO: One would think this could be simplified unless check is paramount
    //                 if (! $result->unfold()) {
    //                     return Shoop::string("");

    //                 } elseif (Shoop::string($endsWith)->isUnfolded("*")) {
    //                     return $store;

    //                 } elseif ($store->string()->endsWithUnfolded($endsWith)) {
    //                     return $store;

    //                 }
    //                 return Shoop::string("");
    //             });
    //     })->noEmpties()->reindex();
    // }
}

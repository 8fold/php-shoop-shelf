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
    private $cacheFolderName = "cache";

    private $client = null;

    public function __construct($main, ...$args)
    {
        parent::__construct($main);
        $this->ghToken    = $args[0];
        $this->ghUsername = $args[1];
        $this->ghRepo     = $args[2];

        if (isset($args[3]) and strlen($args[3]) > 0) {
            $this->useCache = true;
            $this->cacheRootPath   = $args[3];
            $this->cacheFolderName = $args[4];
        }
    }

    public function plus(...$parts)
    {
        $path = $this->parts()->plus(...$parts)->join("/")->start("/");
        return static::fold(
            $path,
            $this->ghToken,
            $this->ghUsername,
            $this->ghRepo,
            $this->cacheRootPath,
            $this->cacheFolderName
        );
    }

    public function dropLast($length = 1)
    {
        $path = $this->parts()->dropLast($length)->join("/")->start("/");
        return static::fold(
            $path,
            $this->ghToken,
            $this->ghUsername,
            $this->ghRepo,
            $this->cacheRootPath,
            $this->cacheFolderName
        );
    }

    // TODO: Make a Store interface
    public function files()
    {
        return $this->repoContent()->entries()->each(function($entry) {
            $e = Shoop::dictionary($entry)->object();
            return ($e->type()->isUnfolded("blob"))
                ? $this->parts()->plus($e->name)->join("/")->unfold()
                : "";
        })->noEmpties();
    }

    public function folders()
    {
        return $this->repoContent()->entries()->each(function($entry) {
            $e = Shoop::dictionary($entry)->object();

            return ($e->type()->isUnfolded("tree"))
                ? $this->parts()->plus($e->name)->join("/")->unfold()
                : "";
        })->noEmpties();
    }

    public function isFolder(Closure $closure = null)
    {
        $bool = $this->repoContent()->hasMember("object", function($result, $object) {
            if ($result->unfold() and $object->dictionary["object"] === null) {
                return false;

            } elseif ($object->hasMemberUnfolded("byteSize")) {
                return false;

            }
            return true;
        });
        return $this->condition($bool, $closure);
    }

    public function isNotFolder(Closure $closure = null)
    {
        $bool = $this->isFolder()->toggle;
        return $this->condition($bool, $closure);
    }

    public function isFile(Closure $closure = null)
    {
        $bool = $this->repoContent()->hasMember("object", function($result, $object) {
            if ($result->unfold() and $object->dictionary["object"] === null) {
                return false;

            } elseif ($object->hasMemberUnfolded("byteSize")) {
                return true;

            }
            return false;
        });
        return $this->condition($bool, $closure);
    }

    public function isNotFile(Closure $closure = null)
    {
        $bool = $this->isFile()->toggle;
        return $this->condition($bool, $closure);
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
        return $closure(
            $bool,
            Shoop::github(
                $value,
                $this->ghToken,
                $this->ghUsername,
                $this->ghRepo,
                $this->cacheRootPath,
                $this->cacheFolderName
            )
        );
    }

    public function content($trim = true, $ignore = [".", "..", ".DS_Store"])
    {
        return $this->isFile(function($result, $client) use ($trim) {
            if ($result->unfold()) {
                $content = $client->repoContent();
                if ($content->isBinary) {
                    die("image or something");
                }
                $content = $this->textContent();
                return ($trim)
                    ? $content->trim()
                    : $content;
            }
            return $client->repoContent()->entries()->each(function($entry) {
                $title = Shoop::dictionary($entry)->name()->start("/");
                return Shoop::github(
                    $this->string()->plus($title),
                    $this->ghToken,
                    $this->ghUsername,
                    $this->ghRepo,
                    $this->cacheRootPath,
                    $this->cacheFolderName
                );
            });
        });
    }

    public function markdown()
    {
        return Shoop::markdown($this->content());
    }

    public function metaMember($memberName)
    {
        $value = $this->markdown()->meta()->{$memberName};
        if ($value === null) {
            return Shoop::string("");
        }
        return Shoop::this($value);
    }

    private function repoContent()
    {
        $query = <<<'QUERY'
        query ($owner: String!, $repo: String!, $path: String!) {
          repository(owner: $owner, name: $repo) {
            object(expression: $path) {
              ... on Blob {
                byteSize
                isBinary
              }
              ... on Tree {
                entries {
                  name
                  type
                }
              }
            }
          }
        }
        QUERY;
        $vars = [
            "owner" => $this->ghUsername,
            "repo" => $this->ghRepo,
            "path" => $this->parts()->countIsGreaterThan(0, function($result, $parts) {
                return ($result->unfold()) ? "master:". $parts->join("/") : "master:";
            })
        ];

        $result = Shoop::dictionary(
            $this->client()->api("graphql")->execute($query, $vars)
        )->object()->data()->repository()->object;

        return Shoop::this($result);
    }

    private function textContent()
    {
        $text = $this->client()->api("repo")->contents()
            ->download($this->ghUsername, $this->ghRepo, $this->value());
        $text = Shoop::string($text);
        return $text;
    }

    public function client()
    {
        if ($this->client === null) {
            $this->client = new Client();

            if ($this->useCache) {
                $adapter = new Local($this->cacheRootPath);
                $filesystem = new Filesystem($adapter);
                $pool = new FilesystemCachePool($filesystem, $this->cacheFolderName);
                $this->client->addCache($pool);
            }

            $this->client->authenticate($this->ghToken, null, Client::AUTH_ACCESS_TOKEN);
        }
        return $this->client;
    }

    public function cache(string $cacheRootPath = "", string $cacheFolderName = "cache")
    {
        $this->cacheRootPath = Shoop::string($cacheRootPath)->countIsGreaterThan(0,
            function($result, $cacheRootPath) use ($cacheFolderName) {
                if ($result->unfold()) {
                    $this->client = null;
                    $this->useCache = true;
                    $this->cacheFolderName = $cacheFolderName;
                    return $cacheRootPath;
                }
                return "";
            });
        return static::fold(
            $this->value(),
            $this->ghToken,
            $this->ghUsername,
            $this->ghRepo,
            $this->cacheRootPath,
            $this->cacheFolderName
        );
    }
}

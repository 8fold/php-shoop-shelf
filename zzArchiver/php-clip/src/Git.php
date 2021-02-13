<?php

namespace Eightfold\Clip;

use Eightfold\Clip\Command;

class Git
{
    private $workingPath = '';

    static public function init(string $workingPath = '.'): Git
    {
        $git = new Git();
        $git->workingPath($workingPath)->initialize();
        return $git;
    }

    static public function tree(string $workingPath = '.'): array
    {
        // git ls-files
        // TODO: This lists all of the files in the repository, including those
        // that are only staged but not yet committed.
        $result = Command::open(static::baseCommand($workingPath))
            ->extra('ls-tree --full-tree -r --name-only HEAD')
            ->close();
        $list = explode("\n", $result->content);
        if (strlen($list[count($list) - 1]) == 0) {
            array_pop($list);
        }
        return $list;
    }

    static public function isCreated(string $workingPath): bool
    {
        return static::exists($workingPath) && static::isRepo($workingPath);
    }

    static public function exists(string $workingPath): bool
    {
        return file_exists($workingPath);
    }

    static public function isRepo(string $workingPath): bool
    {
        $result = Command::open(static::baseCommand($workingPath))
            ->extra('rev-parse')
            ->close();
        return $result->type == 'ok';
    }

    static public function baseCommand(string $workingPath): string
    {
        $escaped = escapeshellarg($workingPath);
        // Run as if git was started in <path> instead of the current working directory.
        $extra = "-C {$escaped}";
        // return "/usr/bin/git ". $extra;
        return "git ". $extra;
    }

    public function __construct()
    {
    }

    private function command()
    {
        return Command::open(static::baseCommand($this->workingPath));
    }

    public function workingPath(string $path = '')
    {
        if (strlen($path) > 0) {
            if ( ! is_dir($path)) {
                mkdir($path);
            }
            $this->workingPath = $path;
            return $this;
        }
        return $this->workingPath;
    }

    public function initialize(): Git
    {
        if (Git::isRepo($this->workingPath)) {
            return $this;
        }

        $result = $this->command()->extra('init')->close();

        if ($result->type == 'error') {
            var_dump("git simple");
            die($result->content);
        }
        return $this;
    }

    public function commit(
        string $shortMessage = '',
        bool $all = true,
        string $longMessage = ''): Git
    {
        $extra = 'commit';
        $suffix = '';
        if ($all) {
            $suffix .= 'a';
        }

        if (strlen($shortMessage) > 0) {
            $suffix .= 'm "'. $shortMessage .'"';
        }

        if (strlen($longMessage) > 0) {
            $suffix .= ' -m "'. $longMessage .'"';
        }

        if (strlen($suffix) > 0) {
            $extra .= ' -'. $suffix;
        }

        $result = $this->command()->extra($extra)->close();

        if ($result->type == 'error') {
            var_dump("git simple, convert to error");
            die('commit failed');
        }
        return $this;
    }

    public function add(string $scope = 'all'): Git
    {
        $files = $this->files('others');
        $processed = [];
        foreach ($files as $file) {
            $processed[] = escapeshellarg($this->workingPath .'/'. $file);
        }
        $fileList = implode(' ', $processed);
        $result = $this->command()->extra('add '. $fileList)->close();

        if ($result->type == 'error') {
            var_dump("git simple trigger error");
            die($result->content);
        }
        return $this;
    }

    public function files(string $state = '')
    {
        $query = $this->command();
        $query->extra('ls-files');
        if (strlen($state) > 0) {
            $query->extra("ls-files --{$state}");
        }
        $result = $query->close();

        if ($result->type == 'error') {
            var_dump("git simple, trigger error");
            die('could not list files');
        }
        $list = explode("\n", $result->content);
        if (strlen($list[count($list) - 1]) == 0) {
            array_pop($list);
        }
        return $list;
    }

    /**
     * TODO: Create commit object?
     *
     * @return [type] [description]
     */
    public function commits()
    {
        $result = $this->command()->extra('log')->close();

        if ($result->type == 'error') {
            var_dump("git simple, trigger error");
            die('could not log commits');
        }
        $commits = explode("\n\n".'commit ', $result->content);
        $int = 0;
        foreach ($commits as $commit) {
            if ($int > 0) {
                $commits[$int] = 'commit '.$commit;
            }
            $int++;
        }
        return $commits;
    }
}

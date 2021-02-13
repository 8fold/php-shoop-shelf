<?php
namespace Eightfold\GitSimple\Tests;

use Eightfold\GitSimple\Tests\BaseTest;

use Eightfold\GitSimple\Git;

use Eightfold\Process\Process;

class GitTest extends BaseTest
{
    public function tearDown()
    {
        $path = $this->path();

        if (file_exists($path)) {
            $this->deleteDirectory($path);
        }
    }

    /**
     * Recursively delete a directory.
     *
     * The directory itself may be optionally preserved.
     *
     * @param  string  $directory
     * @param  bool    $preserve
     * @return bool
     */
    public function deleteDirectory($directory, $preserve = false)
    {
        if (! is_dir($directory)) {
            return false;
        }
        $items = new \FilesystemIterator($directory);
        foreach ($items as $item) {
            // If the item is a directory, we can just recurse into the function and
            // delete that sub-directory otherwise we'll just delete the file and
            // keep iterating through each file until the directory is cleaned.
            if ($item->isDir() && ! $item->isLink()) {
                $this->deleteDirectory($item->getPathname());
            }
            // If the item is just a file, we can go ahead and delete it since we're
            // just looping through and waxing all of the files in this directory
            // and calling directories recursively, so we delete the real path.
            else {
                $this->delete($item->getPathname());
            }
        }
        if (! $preserve) {
            @rmdir($directory);
        }
        return true;
    }

    /**
     * Delete the file at a given path.
     *
     * @param  string|array  $paths
     * @return bool
     */
    public function delete($paths)
    {
        $paths = is_array($paths) ? $paths : func_get_args();
        $success = true;
        foreach ($paths as $path) {
            try {
                if (! @unlink($path)) {
                    $success = false;
                }
            } catch (ErrorException $e) {
                $success = false;
            }
        }
        return $success;
    }

    private function path()
    {
        $exploded = explode('/', __DIR__);
        array_pop($exploded);
        array_pop($exploded);
        array_pop($exploded);
        $imploded = implode('/', $exploded);

        return $imploded .'/tmp';
    }

    private function filePath()
    {
        return $this->path() .'/something.txt';
    }

    private function saveFile(string $content = 'Hello')
    {
        return file_put_contents($this->filePath(), $content);
    }

    private function modifyFile()
    {
        $git = Git::init($this->path());

        $this->saveFile();

        $git->add()->commit('initial save', true);

        $fileList = Git::tree($this->path());
        $this->assertEquals(1, count($fileList));

        $this->saveFile('Hello'."\n\n".'World!');

        $fileList = $git->files('modified');
        $this->assertEquals(1, count($fileList));

        $git->commit('modified file');

        $fileList = Git::tree($this->path());
        $this->assertEquals(1, count($fileList));

        return $git;
    }

    public function testCanInstatiateGit()
    {
        $git = new Git();
        $this->assertNotNull($git);
    }

    public function testCanSetAndGetWorkingPath()
    {
        $git = new Git();
        $git->workingPath($this->path());

        $path = $git->workingPath();
        $this->assertEquals($this->path(), $path);
    }

    public function testCanInitializeRepository()
    {
        $git = new Git();
        $git->workingPath($this->path())->initialize();

        $result = Git::isRepo($this->path());
        $this->assertTrue($result);
    }

    public function testCanGetFiles()
    {
        $git = new Git();
        $git->workingPath($this->path())->initialize();

        $this->saveFile();

        $fileList = $git->files('others');
        $this->assertEquals(1, count($fileList));
    }

    public function testCanCommitFiles()
    {
        $git = Git::init($this->path());

        $this->saveFile();

        $git->commit();

        $fileList = Git::tree($this->path());

        $this->assertEquals(1, count($fileList));
    }

    public function testCanAddFiles()
    {
        $git = Git::init($this->path());

        $this->saveFile();

        $git->add()->commit('short message', true);

        $fileList = Git::tree($this->path());

        $this->assertEquals(1, count($fileList));
    }

    public function testCanModifyFiles()
    {
        $this->modifyFile();
    }

    public function testCanViewCommits()
    {
        $git = $this->modifyFile();

        $commits = $git->commits();

        $this->assertEquals(2, count($commits));
    }
}

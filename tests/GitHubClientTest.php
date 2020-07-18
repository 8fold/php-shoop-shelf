<?php

namespace Eightfold\ShoopExtras\Tests;

use PHPUnit\Framework\TestCase;

use Dotenv\Dotenv;

use Eightfold\ShoopExtras\Shoop;

use Eightfold\ShoopExtras\ESGitHubClient;

class GitHubClientTest extends TestCase
{
    private function client()
    {
        $dotenv = Dotenv::createImmutable(__DIR__)->load();

        $token    = $_ENV["GITHUB_PERSONAL_TOKEN"];
        $username = $_ENV["GITHUB_USERNAME"];
        $repo     = $_ENV["GITHUB_REPO"];

        return Shoop::github("/", $token, $username, $repo);
    }

    private function cacheRoot()
    {
        return Shoop::string(__DIR__)->plus("/data");
    }

    private function rmrf($dir)
    {
        foreach (glob($dir) as $file) {
            if (is_dir($file)) {
                $this->rmrf("$file/*");
                rmdir($file);

            } else {
                unlink($file);

            }
        }
    }

    public function tearDown(): void
    {
        $this->rmrf($this->cacheRoot()->plus("/.cache"));
    }

    public function testIsFolder()
    {
        $actual = $this->client()->isFolder();
        $this->assertTrue($actual->unfold());

        $actual = $this->client()->plus("README.md")->isNotFolder();
        $this->assertTrue($actual->unfold());

        $actual = $this->client()->plus("README.me")->isFolder();
        $this->assertFalse($actual->unfold());
    }

    public function testIsFile()
    {
        $actual = $this->client()->isFile();
        $this->assertFalse($actual->unfold());

        $actual = $this->client()->plus("README.md")->isFile();
        $this->assertTrue($actual->unfold());

        $actual = $this->client()->plus("README.me")->isFile();
        $this->assertFalse($actual->unfold());
    }

    public function testCanListFilesAndFolders()
    {
        $expected = 3;
        $actual = $this->client()->folders()->count();
        $this->assertEquals($expected, $actual->unfold());

        $expected = 8;
        $actual = $this->client()->files()->count();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testCanGetContent()
    {
        $expected = "/README.md";
        $actual = $this->client()->plus("README.md")->content()
            ->startsWith("# 8fold Shoop Extras");
        $this->assertTrue($actual->unfold());

        $expected = 9;
        $actual = $this->client()->plus(".github")->content()->count();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testCanCheckFileExists()
    {
        $actual = $this->client()->plus(
            "tests",
            "data",
            "inner-folder",
            "content.md"
        )->isFile;
        $this->assertTrue($actual);
    }

    public function testCanGetMarkdown()
    {
        $expected = "Hello, World!";
        $actual = $this->client()->plus(
            "tests",
            "data",
            "inner-folder",
            "content.md"
        )->markdown();
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testCanUseCache()
    {
        $this->assertFalse(is_dir($this->cacheRoot()->plus("/.cache")));

        $actual = $this->client()->plus(
            "tests",
            "data",
            "inner-folder",
            "content.md"
        )->cache($this->cacheRoot(), ".cache")->markdown();

        $this->assertTrue(is_dir($this->cacheRoot()->plus("/.cache")));
    }

    public function testCanUsePlus()
    {
        $expected = "Hello, World!";
        $actual = $this->client()->plus(
            "tests",
            "data",
            "inner-folder"
        )->plus("content.md")->markdown();
        $this->assertEquals($expected, $actual->unfold());
    }
}

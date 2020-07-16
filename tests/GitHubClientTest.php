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

    public function testCanGetContent()
    {
        $expected = "/README.md";
        $actual = $this->client()->plus("README.md");
        $this->assertEquals($expected, $actual->unfold());
    }

    public function testCanCheckFileExists()
    {
        $actual = $this->client()->plus(
            "tests",
            "data",
            "inner-folder",
            "content.md"
        )->exists();
        $this->assertTrue($actual->unfold());
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
    // public function testCanGetContent()
    // {
    //     $path = __DIR__ ."/data/inner-folder/subfolder/inner.md";
    //     $content = file_get_contents($path);

    //     $expected = "---\ntitle: Something\n---\n\nMarkdown text\n";
    //     $actual = ESMarkdown::fold($content)->value;
    //     $this->assertEquals($expected, $actual);

    //     $actual = ESMarkdown::fold($content)->unfold();
    //     $this->assertSame($expected, $actual);
    // }

    // public function testCanGetParsed()
    // {
    //     $path = __DIR__ ."/data/inner-folder/subfolder/inner.md";
    //     $content = file_get_contents($path);

    //     $expected = new \stdClass();
    //     $expected->title = "Something";
    //     $actual = ESMarkdown::fold($content)->meta;
    //     $this->assertEquals($expected, $actual);

    //     $expected = "<i>Markdown content</i>";
    //     $actual = ESMarkdown::fold($content)->html([
    //         "text" => "content"
    //     ], [
    //         "<p>" => "<i>",
    //         "</p>" => "</i>"
    //     ]);
    //     $this->assertEquals($expected, $actual->unfold());
    // }

    // public function testExtensions()
    // {
    //     $path = __DIR__ ."/data/table.md";

    //     $expected = "<p>|THead ||:-----||TBody |</p>";
    //     $actual = ESMarkdown::foldFromPath($path)->html();
    //     $this->assertSame($expected, $actual->unfold());

    //     $expected = '<table><thead><tr><th align="left">THead</th></tr></thead><tbody><tr><td align="left">TBody</td></tr></tbody></table>';
    //     $actual = ESMarkdown::foldFromPath($path, TableExtension::class)->html();
    //     $this->assertSame($expected, $actual->unfold());

    //     $path = __DIR__ ."/data/link.md";
    //     $expected = '<p><a rel="noopener noreferrer" target="_blank" href="https://github.com/8fold/php-shoop-extras">Something</a></p><p>Stripped</p>';
    //     $actual = ESMarkdown::foldFromPath($path)->extensions(
    //         ExternalLinkExtension::class
    //     )->html(
    //         [], [], true, true, [
    //             'html_input' => 'strip',
    //             "external_link" => [
    //                 "open_in_new_window" => true
    //             ]
    //         ]
    //     );
    //     $this->assertSame($expected, $actual->unfold());
    // }
}

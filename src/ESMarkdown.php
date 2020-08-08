<?php

namespace Eightfold\ShoopExtras;

use Spatie\YamlFrontMatter\YamlFrontMatter;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;
use League\CommonMark\Extension\{
    GithubFlavoredMarkdownExtension,
    Autolink\AutolinkExtension,
    DisallowedRawHtml\DisallowedRawHtmlExtension,
    Strikethrough\StrikethroughExtension,
    Table\TableExtension,
    TaskList\TaskListExtension
};

use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkRenderer;
use League\CommonMark\Extension\InlinesOnly\InlinesOnlyExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;

use Eightfold\Shoop\Helpers\Type;
use Eightfold\Shoop\Interfaces\Shooped;
use Eightfold\Shoop\Traits\ShoopedImp;
use Eightfold\Shoop\ESString;

use Eightfold\Markup\UIKit;

use Eightfold\ShoopExtras\Shoop;

use Eightfold\Shoop\{
    Interfaces\Foldable,
    Traits\FoldableImp,
    ESArray,
    ESBool,
    ESDictionary
};

class ESMarkdown implements Foldable
{
    use FoldableImp;

    static public function processedMain($main)
    {
        return Type::sanitizeType($main, ESString::class)->unfold();
    }

    static public function foldFromPath($path, ...$extensions)
    {
        return Shoop::store($path)->isNotFile(
            function($result, $path) use ($extensions) {
                return ($result->unfold())
                    ? Shoop::markdown("", ...$extensions)
                    : Shoop::store($path->unfold())->markdown(...$extensions);
            });
    }

    public function string(): ESString
    {
        return Shoop::string($this->main());
    }

    public function extensions(...$args): ESMarkdown
    {
        return static::fold($this->main(), ...$args);
    }

    private function parsed()
    {
        return YamlFrontMatter::parse($this->main());
    }

    public function body()
    {
        $body = $this->parsed()->body();
        return Shoop::string($body);
    }

    public function meta()
    {
        $matter = $this->parsed()->matter();
        return Shoop::object($matter);
    }

    public function content(
        $markdownReplacements = [],
        $caseSensitive = true,
        $trim = true
    )
    {
        $markdownReplacements = Type::sanitizeType(
            $markdownReplacements,
            ESDictionary::class
        )->unfold();

        $caseSensitive = Type::sanitizeType(
            $caseSensitive,
            ESBool::class
        )->unfold();

        $trim = Type::sanitizeType($trim, ESBool::class)->unfold();

        $body = $this->parsed()->body();

        $replaced = Shoop::string($body)
            ->replace($markdownReplacements, $caseSensitive);

        if ($trim) {
            $replaced = $replaced->trim();
        }
        return static::fold($replaced, ...$this->args());
    }

    public function html(
        $markdownReplacements = [],
        $htmlReplacements = [],
        $caseSensitive = true,
        $minified = true,
        $config = [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]
    )
    {
        $content = $this->content($markdownReplacements, $caseSensitive)
            ->unfold();

        $environment = Environment::createCommonMarkEnvironment();
        Shoop::array($this->args())
            ->each(function($extension) use ($environment) {
                $environment->addExtension(new $extension());
            });

        $html = (new CommonMarkConverter($config, $environment))
            ->convertToHtml($content);
        $html = Shoop::string($html)
            ->replace($htmlReplacements, $caseSensitive);
        if ($minified) {
            $html = $html->replace([
                "\t" => "",
                "\n" => "",
                "\r" => "",
                "\r\n" => ""
            ]);
        }
        return Shoop::string($html);
    }
}

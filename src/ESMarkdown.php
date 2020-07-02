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
    ESArray,
    ESBool
};

class ESMarkdown implements Shooped
{
    use ShoopedImp;

    private $extensions = [];

    static public function foldFromPath($path)
    {
        return Shoop::store($path)->isFile(function($result, $path) {
            return ($result)
                ? Shoop::store($path->unfold())->markdown()
                : Shoop::markdown("");
        });
    }

    public function __construct($content, ...$extensions)
    {
        $this->value = Type::sanitizeType($content, ESString::class)->unfold();
        Shoop::array($extensions)->isNotEmpty(function($result, $extensions) {
            if ($result->unfold()) {
                $this->extensions(...$extensions);
            }
        });
    }

    public function extensions(...$extensions)
    {
        Shoop::array($extensions)->isNotEmpty(function($result, $extensions) {
            $this->extensions = ($result->unfold())
                ? $extensions->noEmpties()
                : $extensions->plus(
                    GithubFlavoredMarkdownExtension::class,
                    ExternalLinkExtension::class
                )->noEmpties();
        });
        return $this;
    }

    private function parsed()
    {
        return YamlFrontMatter::parse($this->value());
    }

    public function meta()
    {
        $matter = $this->parsed()->matter();
        return Shoop::object($matter);
    }

    public function content($markdownReplacements = [], $caseSensitive = true, $trim = true)
    {
        $markdownReplacements = Type::sanitizeType($markdownReplacements, ESArray::class)->unfold();
        $caseSensitive = Type::sanitizeType($caseSensitive, ESBool::class)->unfold();
        $trim = Type::sanitizeType($trim, ESBool::class)->unfold();
        $body = $this->parsed()->body();
        $replaced = Shoop::string($body)
            ->replace($markdownReplacements, $caseSensitive);
        if ($trim) {
            $replaced = $replaced->trim();
        }
        return static::fold($replaced);
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
        Shoop::array($this->extensions)->each(function($extension) use ($environment) {
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

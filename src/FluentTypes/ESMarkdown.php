<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Foldable\Foldable;
use Eightfold\Foldable\FoldableImp;

use Spatie\YamlFrontMatter\YamlFrontMatter;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;

use Eightfold\Shoop\Shooped;

use Eightfold\ShoopShelf\Shoop;

class ESMarkdown implements Foldable
{
    use FoldableImp;

    public function __call(string $name, array $args = [])
    {
        return $this->meta()->{$name};
    }

    public function extensions(...$args): ESMarkdown
    {
        $main = $this->main();
        return static::fold($main, ...$args);
    }

    private function parsed()
    {
        return YamlFrontMatter::parse($this->main());
    }

    public function body(): Shooped
    {
        return Shoop::this(
            $this->parsed()->body()
        );
    }

    public function meta()
    {
        return (object) $this->parsed()->matter();
    }

    public function content(
        $markdownReplacements = [],
        $caseSensitive = true,
        $trim = true
    ): Shooped
    {
        return $this->replace(
            $this->body()->unfold(),
            $markdownReplacements,
            $caseSensitive
        );
        // $main         = $this->body()->unfold();
        // $needles      = array_keys($markdownReplacements);
        // $replacements = array_values($markdownReplacements);
        // $string       = str_replace($needles, $replacements, $main);

        // return Shoop::this($string);
        // return $this->body()
        //     ->replace($markdownReplacements, $caseSensitive);
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
        $content = $this->content($markdownReplacements, $caseSensitive)->unfold();

        $environment = Environment::createCommonMarkEnvironment();
        $args        = $this->args();
        foreach ($args as $extension) {
            $environment->addExtension(new $extension());
        }

        $html = (new CommonMarkConverter($config, $environment))
            ->convertToHtml($content);

        $html = $this->replace($html, $htmlReplacements, $caseSensitive)->unfold();

        if ($minified) {
            $html = $this->replace(
                $html,
                [
                    "\t" => "",
                    "\n" => "",
                    "\r" => "",
                    "\r\n" => ""
                ]
            )->unfold();
        }
        return Shoop::this($html);
    }

    private function replace($using, $replacements = [], $caseSensitive = true)
    {
        $main         = $using;
        $needles      = array_keys($replacements);
        $replacements = array_values($replacements);
        $string       = str_replace($needles, $replacements, $main);

        return Shoop::this($string);
    }
}

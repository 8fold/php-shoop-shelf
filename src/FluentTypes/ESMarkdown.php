<?php
declare(strict_types=1);

namespace Eightfold\ShoopShelf\FluentTypes;

use Eightfold\Foldable\Foldable;
use Eightfold\Foldable\FoldableImp;

use Spatie\YamlFrontMatter\YamlFrontMatter;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;

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

    public function body(): ESString
    {
        $body = $this->parsed()->body();
        return Shoop::string($body);
    }

    public function meta()
    {
        return (object) $this->parsed()->matter();
    }

    public function content(
        $markdownReplacements = [],
        $caseSensitive = true,
        $trim = true
    ): ESString
    {
        $replaced = $this->body()
            ->replace($markdownReplacements, $caseSensitive);

        return ($trim) ? $replaced->trim() : $replaced;
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
        $environment = Environment::createCommonMarkEnvironment();
        $args        = $this->args();
        foreach ($args as $extension) {
            $environment->addExtension(new $extension());
        }

        $content = $this->content($markdownReplacements, $caseSensitive)->unfold();

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

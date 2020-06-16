<?php

namespace Eightfold\ShoopExtras;

use Spatie\YamlFrontMatter\YamlFrontMatter;

use Eightfold\Shoop\Helpers\Type;
use Eightfold\Shoop\Interfaces\Shooped;
use Eightfold\Shoop\Traits\ShoopedImp;
use Eightfold\Shoop\ESString;

use Eightfold\Markup\UIKit;

use Eightfold\ShoopExtras\{
    Shoop,
    ESBool
};

class ESMarkdown implements Shooped
{
    use ShoopedImp;

    public function __construct($content)
    {
        $this->value = Type::sanitizeType($content, ESString::class)->unfold();
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

    public function content($markdownReplacements = [], $caseSensitive = true)
    {
        $body = $this->parsed()->body();
        $replaced = Shoop::string($body)
            ->replace($markdownReplacements, $caseSensitive);
        return static::fold($replaced);
    }

    public function html(
        $markdownReplacements = [],
        $htmlReplacements = [],
        $caseSensitive = true,
        $minified = true
    )
    {
        $content = $this->content($markdownReplacements, $caseSensitive)
            ->unfold();
        $cap = UIKit::p("Hello");
        // $html = UIKit::markdown($content)->unfold();
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

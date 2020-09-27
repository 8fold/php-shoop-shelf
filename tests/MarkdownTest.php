<?php

namespace Eightfold\ShoopShelf\Tests;

use PHPUnit\Framework\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\ShoopShelf\FluentTypes\ESString;
use Eightfold\ShoopShelf\FluentTypes\ESMarkdown;

use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;

/**
 * @group Markdown
 */
class MarkdownTest extends TestCase
{
    /**
     * @test
     */
    public function file_content_is_markdown()
    {
        $path = __DIR__ ."/data/inner-folder/subfolder/inner.md";

        AssertEquals::applyWith(
            $path,
            "string",
            5.23, // 4.85, // 4.58 // 2.91 // 2.76 // 2.64 // 2.63 // 2.06 // 2.05 // 1.99
            290 // 260
        )->unfoldUsing(
            Shoop::store(__DIR__)->plus(
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            )
        );

        AssertEquals::applyWith(
            "---\ntitle: Something\n---\n\nMarkdown text\n",
            "string",
            1.08, // 0.33
            13
        )->unfoldUsing(
            Shoop::store(__DIR__)->plus(
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            )->content()
        );

        AssertEquals::applyWith(
            "---\ntitle: Something\n---\n\nMarkdown text\n",
            "string"
        )->unfoldUsing(
            Shoop::store(__DIR__)->plus(
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            )->markdown()
        );

        $actual = Shoop::store(__DIR__)->plus(
            "data",
            "inner-folder",
            "subfolder",
            "inner.md"
        )->content();
        $this->assertTrue(is_a($actual, ESString::class));

        $actual = Shoop::store(__DIR__)->plus(
            "data",
            "inner-folder",
            "subfolder",
            "inner.md"
        )->content()->markdown();
        $this->assertTrue(is_a($actual, ESMarkdown::class));

        $actual = Shoop::store(__DIR__)->plus(
            "data",
            "inner-folder",
            "subfolder",
            "inner.md"
        )->markdown();
        $this->assertTrue(is_a($actual, ESMarkdown::class));
    }

    /**
     * @test
     */
    public function meta_and_replacements()
    {
        AssertEquals::applyWith(
            "Something",
            "string",
            10.24, // 6.19 // 5.99 // 5.64 // 4.48 // 4.4
            353 // 304
        )->unfoldUsing(
            Shoop::store(__DIR__)->plus(
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            )->markdown()->title()
        );

        AssertEquals::applyWith(
            '<i>Markdown content</i>',
            "string",
            35.48, // 11.92
            784 // 768
        )->unfoldUsing(
            Shoop::markdown("Markdown content")->html([
                "text" => "content"
            ], [
                "<p>" => "<i>",
                "</p>" => "</i>"
            ])
        );
    }

    /**
     * @test
     */
    public function extensions()
    {
        $path = __DIR__ ."/data/table.md";

        AssertEquals::applyWith(
            '<table><thead><tr><th align="left">THead</th></tr></thead><tbody><tr><td align="left">TBody</td></tr></tbody></table>',
            "string",
            31.48, // 21.9
            1141 // 928
        )->unfoldUsing(
            Shoop::store($path)->markdown()
                ->extensions(TableExtension::class)->html()
        );

        $path = __DIR__ ."/data/link.md";

        AssertEquals::applyWith(
            '<p><a rel="noopener noreferrer" target="_blank" href="https://github.com/8fold/php-shoop-extras">Something</a></p><p>Stripped</p>',
            "string",
            9.07, // 5.41
            179 // 115
        )->unfoldUsing(
            Shoop::store($path)->markdown()
                ->extensions(ExternalLinkExtension::class)
                ->html(
                    [], [], true, true, [
                        'html_input' => 'strip',
                        "external_link" => [
                            "open_in_new_window" => true
                        ]
                    ]
                )
        );
    }
}

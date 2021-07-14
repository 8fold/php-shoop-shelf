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
            11.65, // 4.36, // 4.33, // 4.22, // 3.66, // 3.51,
            416 // 405 // 348 // 346 // 345 // 344
            // 5.23, // 4.85, // 4.58 // 2.91 // 2.76 // 2.64 // 2.63 // 2.06 // 2.05 // 1.99
            // 290 // 260
        )->unfoldUsing(
            Shoop::store(__DIR__)->append([
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            ])
        );

        AssertEquals::applyWith(
            "---\ntitle: Something\n---\n\nMarkdown text\n",
            "string",
            17.66, // 0.17, // 0.13, // 0.12, // 0.11,
            392 // 309 // 235
        )->unfoldUsing(
            Shoop::store(__DIR__)->append([
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            ])->content()
        );

        AssertEquals::applyWith(
            "---\ntitle: Something\n---\n\nMarkdown text\n",
            "string",
            0.38, // 0.24, // 0.22, // 0.21, // 0.2, // 0.19,
            13 // 12 // 11 // 10
        )->unfoldUsing(
            Shoop::store(__DIR__)->append([
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            ])->markdown()
        );
    }

    /**
     * @test
     */
    public function meta_and_replacements()
    {
        AssertEquals::applyWith(
            "Something",
            "string",
            8.85,
            793 // 704 // 703
        )->unfoldUsing(
            Shoop::store(__DIR__)->append([
                "data",
                "inner-folder",
                "subfolder",
                "inner.md"
            ])->markdown()->title()
        );

        AssertEquals::applyWith(
            '<i>Markdown content</i>',
            "string",
            25.67, // 10.02, // 8.44,
            943 // 854 // 848 // 768
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
            13.25, // 12.88, // 12.17,
            1138
            // 31.48, // 21.9
            // 1141 // 928
        )->unfoldUsing(
            Shoop::store($path)->markdown()
                ->extensions(TableExtension::class)->html()
        );

        $path = __DIR__ ."/data/link.md";

        AssertEquals::applyWith(
            '<p><a rel="noopener noreferrer" target="_blank" href="https://github.com/8fold/php-shoop-extras">Something</a></p><p>Stripped</p>',
            "string",
            4.67, // 3.23, // 2.62,
            179 // 117
            // 9.07, // 5.41
            // 179 // 115
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

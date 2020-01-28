<?php
namespace DoW\CommonMark\Tests\Unit\Block\Renderer;

use DoW\CommonMark\ImplicitFigures\ImplicitFigures;
use DoW\CommonMark\ImplicitFigures\ImplicitFiguresRenderer;

use League\CommonMark\Block\Element as BlockElement;
use League\CommonMark\HtmlElement;
use League\CommonMark\HtmlRenderer;
use PHPUnit\Framework\TestCase;

use DoW\CommonMark\Tests\Unit\FakeHtmlRenderer;

class ImplicitFiguresRendererTest extends TestCase
{
    /**
     * @var ImplicitFiguresRenderer
     */
    protected $renderer;

    /**
     * @var HtmlRenderer
     */
    protected $htmlRenderer;

    protected function setUp() : void
    {
        $this->renderer = new ImplicitFiguresRenderer();
        $env = \League\CommonMark\Environment::createCommonMarkEnvironment();
        $env->addInlineRenderer('DoW\CommonMark\ImplicitFigures\FigCaption', new \DoW\CommonMark\ImplicitFigures\FigCaptionRenderer());
        $this->htmlRenderer = new \League\CommonMark\HtmlRenderer($env);
    }

    /**
     * @param string      $src
     * @param string|null $title
     * @param string|null $title
     *
     * @dataProvider dataForTestImplicitFigures
     */
    public function testRenderImplicitFigures($src, $title = null, $link = null)
    {
        $figure = $this->createImplicitFiguresBlock($src, $title, $link);
        $result = $this->renderer->render($figure, $this->htmlRenderer);
        $this->assertTrue($result instanceof HtmlElement);

        $this->assertEquals('figure', $result->getTagName());
        $title = $title ? trim($title) : $title;
        $base = "<img src=\"$src\" alt=\"$title\" ";
        if ($title && !empty(trim($title))) {
            $title = trim($title);
            $base .= "title=\"$title\" /><figcaption>$title</figcaption>";
        } else {
            $base .= '/>';
        }
        if ($link && !empty(trim($link))) {
            $link = trim($link);
            $this->assertStringContainsString("<a href=\"$link\">$base</a>", $result->getContents(true));
        } else {
            $this->assertStringContainsString($base, $result->getContents(true));
        }
    }

    public function dataForTestImplicitFigures()
    {
        return [
            [null, null, null],
            ['http://test.png'],
            ['http://test.png', ''],
            ['http://test.png', 'Super Test'],
            ['http://test.png', 'Super Test 2', 'http://link.to'],
            ['http://test.png', null, 'http://link.to'],
            ['http://test.png', ' ', 'http://link.to'],
        ];
    }

    /**
     */
    public function testRenderWithInvalidType()
    {
        $this->expectException('\InvalidArgumentException');
        $inline = $this->getMockForAbstractClass(BlockElement\AbstractBlock::class);

        $this->renderer->render($inline, $this->htmlRenderer);
    }

    protected function createImplicitFiguresBlock($src, $title = null, $link = null)
    {
        $block = new ImplicitFigures(
            $src,
            $title,
            $link
        );

        return $block;
    }
}

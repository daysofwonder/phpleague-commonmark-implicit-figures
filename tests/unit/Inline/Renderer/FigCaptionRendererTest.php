<?php
namespace DoW\CommonMark\Tests\Unit\Inline\Renderer;

use DoW\CommonMark\ImplicitFigures\FigCaption;
use DoW\CommonMark\ImplicitFigures\FigCaptionRenderer;

use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element as InlineElement;
use PHPUnit\Framework\TestCase;

use DoW\CommonMark\Tests\Unit\FakeHtmlRenderer;

class FigCaptionRendererTest extends TestCase
{
    /**
     * @var FigCaptionRenderer
     */
    protected $renderer;

    protected function setUp() : void
    {
        $this->renderer = new FigCaptionRenderer();
    }

    public function testRender()
    {
        $inline = new FigCaption();
        $inline->data['attributes'] = ['id' => 'foo'];
        $fakeRenderer = new FakeHtmlRenderer();

        $result = $this->renderer->render($inline, $fakeRenderer);

        $this->assertTrue($result instanceof HtmlElement);
        $this->assertEquals('figcaption', $result->getTagName());
        $this->assertStringContainsString('::inlines::', $result->getContents(true));
        $this->assertEquals(['id' => 'foo'], $result->getAllAttributes());
    }

    /**
     */
    public function testRenderWithInvalidType()
    {
        $this->expectException('\InvalidArgumentException');
        $inline = $this->getMockForAbstractClass(InlineElement\AbstractInline::class);
        $fakeRenderer = new FakeHtmlRenderer();

        $this->renderer->render($inline, $fakeRenderer);
    }
}

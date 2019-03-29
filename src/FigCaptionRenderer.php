<?php
namespace DoW\CommonMark\ImplicitFigures;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;
use League\CommonMark\Util\Xml;

class FigCaptionRenderer implements InlineRendererInterface
{
    /**
     * @param FigCaption               $inline
     * @param ElementRendererInterface $htmlRenderer
     *
     * @return HtmlElement
     */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof FigCaption)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . get_class($inline));
        }

        $attrs = [];
        foreach ($inline->getData('attributes', []) as $key => $value) {
            $attrs[$key] = Xml::escape($value);
        }

        return new HtmlElement('figcaption', $attrs, $htmlRenderer->renderInlines($inline->children()));
    }
}

<?php
namespace DoW\CommonMark\ImplicitFigures;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\Xml;

class FigCaptionRenderer implements NodeRendererInterface
{
    /**
     * @param FigCaption               $inline
     * @param ElementRendererInterface $htmlRenderer
     *
     * @return HtmlElement
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof FigCaption)) {
            throw new \InvalidArgumentException('Incompatible inline type: ' . get_class($node));
        }

        $attrs = [];
        foreach ($node->data['attributes'] as $key => $value) {
            $attrs[$key] = Xml::escape($value);
        }

        return new HtmlElement('figcaption', $attrs, $childRenderer->renderNodes($node->children()));
    }
}

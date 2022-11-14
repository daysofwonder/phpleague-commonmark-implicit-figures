<?php
namespace DoW\CommonMark\ImplicitFigures;

use League\CommonMark\Node\Node;
use League\CommonMark\Extension\CommonMark\Node\Block\ListBlock;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\Xml;

class ImplicitFiguresRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!($node instanceof ImplicitFigures)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . get_class($node));
        }

        $attrs = [];
        foreach ($node->data['attributes'] as $key => $value) {
            $attrs[$key] = Xml::escape($value);
        }

        return new HtmlElement(
            'figure',
            $attrs,
            $childRenderer->getInnerSeparator() .
                $childRenderer->renderNodes(
                    $node->children()
                ) .
                $childRenderer->getInnerSeparator()
        );
    }
}

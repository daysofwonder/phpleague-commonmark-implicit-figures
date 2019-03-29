<?php
namespace DoW\CommonMark\ImplicitFigures;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\Block\Renderer\BlockRendererInterface;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Util\Xml;

class ImplicitFiguresRenderer implements BlockRendererInterface
{
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, $inTightList = false)
    {
        if (!($block instanceof ImplicitFigures)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . get_class($block));
        }

        $attrs = [];
        foreach ($block->getData('attributes', []) as $key => $value) {
            $attrs[$key] = Xml::escape($value);
        }

        return new HtmlElement(
            'figure',
            $attrs,
            $htmlRenderer->getOption('inner_separator', "\n") .
                $htmlRenderer->renderInlines(
                    $block->children()
                ) .
                $htmlRenderer->getOption('inner_separator', "\n")
        );
    }
}

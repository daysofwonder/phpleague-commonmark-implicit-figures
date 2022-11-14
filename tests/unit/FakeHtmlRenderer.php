<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DoW\CommonMark\Tests\Unit;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Node\Inline\AbstractInline;

class FakeHtmlRenderer implements ChildNodeRendererInterface
{
    protected $options;

    /**
     * @param string     $option
     * @param mixed|null $value
     */
    public function setOption($option, $value)
    {
        $this->options[$option] = $value;
    }

    /**
     * @param string     $option
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    public function getOption($option, $default = null)
    {
        if (!isset($this->options[$option])) {
            return $default;
        }

        return $this->options[$option];
    }

    // public function renderInline(AbstractInline $inline) : string
    // {
    //     return "::inline::";
    // }

    /**
     * @param AbstractInline[] $inlines
     *
     * @return string
     */
    public function renderNodes(iterable $nodes) : string
    {
        $output = '::';

        if (count($nodes) === 0) {
            return '';
        }

        if ($nodes[0] instanceof AbstractBlock) {
            $output .= 'block';
        } else {
            $output .= 'inline';
        }

        if (count($nodes) > 1) {
            $output .= 's';
        }

        return $output .= '::';
        //
        // foreach ($nodes as $node) {
        //     if (! $isFirstItem && $node instanceof AbstractBlock) {
        //         $output .= $this->getBlockSeparator();
        //     }
        //
        //     $output .= $this->renderNode($node);
        //
        //     $isFirstItem = false;
        // }
        // return '::inlines::';
    }
    //
    // /**
    //  * @param AbstractBlock $block
    //  * @param bool          $inTightList
    //  *
    //  * @throws \RuntimeException
    //  *
    //  * @return string
    //  */
    // public function renderBlock(AbstractBlock $block, $inTightList = false) : string
    // {
    //     return '::block::';
    // }
    //
    // /**
    //  * @param AbstractBlock[] $blocks
    //  * @param bool            $inTightList
    //  *
    //  * @return string
    //  */
    // public function renderBlocks(iterable $blocks, $inTightList = false) : string
    // {
    //     return '::blocks::';
    // }

    public function getBlockSeparator() : string
    {
        return '';
    }

    public function getInnerSeparator() : string
    {
        return '';
    }
}

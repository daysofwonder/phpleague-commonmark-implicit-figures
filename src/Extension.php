<?php
namespace DoW\CommonMark\ImplicitFigures;

use League\CommonMark\ConfigurableEnvironmentInterface;
use League\CommonMark\Extension\Extension as LeagueExtension;

class Extension extends LeagueExtension
{

    /**
     * Returns a list of block parsers to add to the existing list
     *
     * @return BlockParserInterface[]
     */
    public function getBlockParsers()
    {
        return [new ImplicitFiguresBlockParser()];
    }

    /**
     * Returns a list of block renderers to add to the existing list
     *
     * The list keys are the block class names which the corresponding value (renderer) will handle.
     *
     * @return BlockRendererInterface[]
     */
    public function getBlockRenderers()
    {
        return [ 'DoW\CommonMark\ImplicitFigures\ImplicitFigures' => new ImplicitFiguresRenderer()];
    }

    /**
     * Returns a list of inline renderers to add to the existing list
     *
     * The list keys are the inline class names which the corresponding value (renderer) will handle.
     *
     * @return InlineRendererInterface[]
     */
    public function getInlineRenderers()
    {
        return [ 'DoW\CommonMark\ImplicitFigures\FigCaption' => new FigCaptionRenderer()];
    }
}

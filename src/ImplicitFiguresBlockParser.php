<?php
namespace DoW\CommonMark\ImplicitFigures;

use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;
use League\CommonMark\Parser\Block\BlockStart;

final class ImplicitFiguresBlockParser extends AbstractBlockContinueParser
{
    private ImplicitFigures $block;

    public function __construct(ImplicitFigures $block)
    {
        $this->block = $block;
    }

    public function getBlock() : ImplicitFigures
    {
        return $this->block;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        return BlockContinue::finished();
    }

    public static function createBlockStartParser() : BlockStartParserInterface
    {
        return new class implements BlockStartParserInterface
        {
            /**
             * @param ContextInterface $context
             * @param Cursor           $cursor
             *
             * @return bool
             */
            public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
            {
                $tmpCursor = clone $cursor;
                $tmpCursor->advanceToNextNonSpaceOrTab();
                $rest = $tmpCursor->getRemainder();

                // Figure pattern: ^!\[(?P<title>[^]]*)\]\((?P<src>[^)]*)\)\s*$
                // Figure with link pattern: ^\[!\[(?P<title>[^]]*)\]\((?P<src>[^)]*)\)\]\((?P<link>[^)]*)\)\s*$

                $matches = [];
                $imgBlockPattern = '!\[(?P<title>[^]]*)\]\((?P<src>[^)]*)\)';

                $src = null;
                $figures = null;
                $link = null;

                if (preg_match("/^$imgBlockPattern\s*$/", $rest, $matches) === 1) {
                    $src = trim($matches['src']);
                    $title = empty(trim($matches['title'])) ? null : trim($matches['title']);
                } elseif (preg_match("/^\[$imgBlockPattern\]\((?P<link>[^)]*)\)\s*$/", $rest, $matches) === 1) {
                    $src = trim($matches['src']);
                    $title = empty(trim($matches['title'])) ? null : trim($matches['title']);
                    $link = trim($matches['link']);
                } else {
                    return BlockStart::none();
                }

                $markerLength = strlen($matches[0]);

                // Make sure we have nothing or spaces after
                $nextChar = $tmpCursor->peek($markerLength);
                if (!($nextChar === null || $nextChar === "\t" || $nextChar === ' ')) {
                    return false;
                }

                // We've got a match! Advance offset and calculate padding
                $cursor->advanceToNextNonSpaceOrTab(); // to start of marker
                $cursor->advanceBy($markerLength, true); // to end of marker

                return BlockStart::of(new ImplicitFiguresBlockParser(
                    new ImplicitFigures(
                        $matches['src'],
                        empty(trim($matches['title'])) ? null : $matches['title'],
                        $matches['link'] ?? null
                    )
                ))->at($cursor);
                //
                // $parserState->addBlock(
                //     new ImplicitFigures(
                //         $matches['src'],
                //         empty(trim($matches['title'])) ? null : $matches['title'],
                //         $matches['link'] ?? null
                //     )
                // );
                //
                // return true;
            }
        };
    }
}

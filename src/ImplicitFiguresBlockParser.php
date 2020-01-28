<?php
namespace DoW\CommonMark\ImplicitFigures;

use League\CommonMark\Block\Parser\BlockParserInterface;
use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;

class ImplicitFiguresBlockParser implements BlockParserInterface
{
    /**
     * @param ContextInterface $context
     * @param Cursor           $cursor
     *
     * @return bool
     */
    public function parse(ContextInterface $context, Cursor $cursor) : bool
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
            return false;
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

        $context->addBlock(
            new ImplicitFigures(
                $matches['src'],
                empty(trim($matches['title'])) ? null : $matches['title'],
                $matches['link'] ?? null
            )
        );

        return true;
    }
}

<?php
namespace DoW\CommonMark\ImplicitFigures;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Inline\Element\HtmlElement;
use League\CommonMark\Parser\Cursor;
use Dflydev\DotAccessData\Data;

class ImplicitFigures extends AbstractBlock
{
    /**
     * @var bool
     */
    protected $tight = false;
    /**
     * @var array
     *
     * Used for storage of arbitrary data
     */
    public Data $data;

    /**
     * @var string
     *
     * Source for the figure's image
     */
    public $src = null;

    /**
     * @var string
     *
     * Source for the figure's title, if there is one.
     */
    public $title = null;

    /**
     * @var string
     *
     * Href for the link figure's container, if there is one.
     */
    public $link = null;

    /**
     * @param string|null      $src
     * @param string|null $title
     * @param string|null $title
     * @param array       $attributes
     */
    public function __construct(?string $src, ?string $title = null, ?string $link = null, array $attributes = [])
    {
        $this->src = $src && trim($src) ? trim($src) : '';
        $this->title = $title && trim($title) ? trim($title) : null;
        $this->link = $link ? trim($link) : null;
        // $attributes['data-type'] = 'image';
        $this->data = new Data(['attributes' => $attributes]);

        $parent = $this;
        if ($this->link) {
            $link = new Link($this->link);
            $this->appendChild($link);
            $parent = $link;
        }

        $image = new Image($this->src, $this->title, $this->title);
        $parent->appendChild($image);

        if ($this->title) {
            $figcaption = new FigCaption();
            $figcaption->appendChild(new Text($this->title));
            $parent->appendChild($figcaption);
        }
    }

    public function canContain(AbstractBlock $block) : bool
    {
        return false;
    }

    /**
     * Returns true if block type can accept lines of text
     *
     * @return bool
     */
    public function acceptsLines() : bool
    {
        return false;
    }

    /**
     * Whether this is a code block
     *
     * @return bool
     */
    public function isCode() : bool
    {
        return false;
    }

    /**
     * @param Cursor $cursor
     *
     * @return bool
     */
    public function matchesNextLine(Cursor $cursor) : bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isTight()
    {
        return $this->tight;
    }
}

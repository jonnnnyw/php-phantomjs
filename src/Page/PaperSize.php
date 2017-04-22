<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Page;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class PaperSize implements \JsonSerializable
{
    /**
     * Width.
     *
     * @var int
     */
    private $width;

    /**
     * Height.
     *
     * @var int
     */
    private $height;

    /**
     * Margin.
     *
     * @var int|JonnyW\PhantomJs\Page\Margin
     */
    protected $margin;

    /**
     * Header.
     *
     * @var \JonnyW\PhantomJs\Page\PaperBlock
     */
    protected $header;

    /**
     * Footer.
     *
     * @var \JonnyW\PhantomJs\Page\PaperBlock
     */
    protected $footer;

    /**
     * Internal constructor.
     *
     * @param int                              $width
     * @param int                              $height
     * @param int|JonnyW\PhantomJs\Page\Margin $margin (default: 0)
     */
    public function __construct($width, $height, $margin = 0)
    {
        $this->width = (int) $width;
        $this->height = (int) $height;
        $this->margin = $margin;
    }

    /**
     * Set header.
     *
     * @param \JonnyW\PhantomJs\Page\PaperBlock $header
     *
     * @return |JonnyW\PhantomJs\Page\PaperSize
     */
    public function setHeader(PaperBlock $header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Set footer.
     *
     * @param \JonnyW\PhantomJs\Page\PaperBlock $footer
     *
     * @return |JonnyW\PhantomJs\Page\PaperSize
     */
    public function setFooter(PaperBlock $footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Format data for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter(array(
            'width' => $this->width,
            'height' => $this->height,
            'margin' => $this->margin,
            'header' => $this.header,
            'footer' => $this->footer,
        ));
    }
}

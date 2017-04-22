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
class PaperFormat extends PaperSize
{
    /**
     * Format.
     *
     * @var string
     */
    private $format;

    /**
     * Orientation.
     *
     * @var string
     */
    private $orientation;

    /**
     * Internal constructor.
     *
     * @param string                           $format
     * @param string                           $orientation (default: 'portrait')
     * @param int|JonnyW\PhantomJs\Page\Margin $margin      (default: 0)
     */
    public function __construct($format, $orientation = 'portrait', $margin = 0)
    {
        $this->format = $format;
        $this->orientation = $orientation;
        $this->margin = $margin;
    }

    /**
     * Format data for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_filter(array(
            'format' => $this->format,
            'orientation' => $this->orientation,
            'margin' => $this->margin,
            'header' => $this.header,
            'footer' => $this->footer,
        ));
    }
}

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
class ClipRect implements \JsonSerializable
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
     * Top.
     *
     * @var int
     */
    private $top;

    /**
     * Left.
     *
     * @var int
     */
    private $left;

    /**
     * Internal constructor.
     *
     * @param int $width
     * @param int $height
     * @param int $top    (default: 0)
     * @param int $left   (default: 0)
     */
    public function __construct($width, $height, $top = 0, $left = 0)
    {
        $this->width = (int) $width;
        $this->height = (int) $height;
        $this->top = (int) $top;
        $this->left = (int) $left;
    }

    /**
     * Format data for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'top' => $this->top,
            'left' => $this->left,
            'width' => $this->width,
            'height' => $this->height,
        );
    }
}

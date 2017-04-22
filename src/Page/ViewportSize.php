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
class ViewportSize implements \JsonSerializable
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
     * Internal constructor.
     *
     * @param int $width
     * @param int $height
     */
    public function __construct($width, $height)
    {
        $this->width = (int) $width;
        $this->height = (int) $height;
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
        );
    }
}

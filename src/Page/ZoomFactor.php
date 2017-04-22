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
class ZoomFactor implements \JsonSerializable
{
    /**
     * Zoom.
     *
     * @var int
     */
    private $zoom;

    /**
     * Internal constructor.
     *
     * @param int $zoom
     */
    public function __construct($zoom)
    {
        $this->zoom = (int) $zoom;
    }

    /**
     * Format data for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'zoom' => $this->zoom,
        );
    }
}

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
class Margin implements \JsonSerializable
{
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
     * Bottom.
     *
     * @var int
     */
    private $bottom;

    /**
     * Right.
     *
     * @var int
     */
    private $right;

    /**
     * Internal constructor.
     *
     * @param int $top    (default: 0)
     * @param int $left   (default: 0)
     * @param int $bottom (default: 0)
     * @param int $right  (default: 0)
     */
    public function __construct($top = 0, $left = 0, $bottom = 0, $right = 0)
    {
        $this->top = (int) $top;
        $this->left = (int) $left;
        $this->bottom = (int) $bottom;
        $this->right = (int) $right;
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
            'bottom' => $this->bottom,
            'right' => $this->right,
        );
    }
}

<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Message;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface CaptureRequestInterface
{
    /**
     * Set viewport size.
     *
     * @access public
     * @param int $width
     * @param int $height
     * @param int $top    (default: 0)
     * @param int $left   (default: 0)
     */
    public function setCaptureDimensions($width, $height, $top = 0, $left = 0);

    /**
     * Get rect top.
     *
     * @access public
     * @return int
     */
    public function getRectTop();

    /**
     * Get rect left.
     *
     * @access public
     * @return int
     */
    public function getRectLeft();

    /**
     * Get rect width.
     *
     * @access public
     * @return int
     */
    public function getRectWidth();

    /**
     * Get rect height.
     *
     * @access public
     * @return int
     */
    public function getRectHeight();

    /**
     * Set file to save screen capture.
     *
     * @access public
     * @param string $file
     */
    public function setCaptureFile($file);

    /**
     * Get capture file.
     *
     * @access public
     * @return string
     */
    public function getCaptureFile();
}

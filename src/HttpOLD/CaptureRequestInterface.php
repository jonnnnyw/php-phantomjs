<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Http;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface CaptureRequestInterface
{
    /**
     * Set viewport size.
     *
     * @param int $width
     * @param int $height
     * @param int $top    (default: 0)
     * @param int $left   (default: 0)
     */
    public function setCaptureDimensions($width, $height, $top = 0, $left = 0);

    /**
     * Get rect top.
     *
     * @return int
     */
    public function getRectTop();

    /**
     * Get rect left.
     *
     * @return int
     */
    public function getRectLeft();

    /**
     * Get rect width.
     *
     * @return int
     */
    public function getRectWidth();

    /**
     * Get rect height.
     *
     * @return int
     */
    public function getRectHeight();

    /**
     * Set file to save output.
     *
     * @param string $file
     */
    public function setOutputFile($file);

    /**
     * Get output file.
     *
     * @return string
     */
    public function getOutputFile();

    /**
     * Get image format of the capture.
     *
     * @return string
     */
    public function getFormat();

    /**
     * Set image format of capture.
     * options: pdf, png, jpeg, bmp, ppm, gif.
     *
     * @param string $format
     */
    public function setFormat($format);

    /**
     * Get quality of capture.
     *
     * @return string
     */
    public function getQuality();

    /**
     * Set quality of the capture.
     * example: 0 - 100.
     *
     * @param int $format
     */
    public function setQuality($quality);
}

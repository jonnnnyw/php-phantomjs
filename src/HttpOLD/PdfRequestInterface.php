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
interface PdfRequestInterface
{
    /**
     * Set paper width.
     *
     * @param string $width
     */
    public function setPaperWidth($width);

    /**
     * Get paper width.
     *
     * @return string
     */
    public function getPaperWidth();

    /**
     * Set paper height.
     *
     * @param string $height
     */
    public function setPaperHeight($height);

    /**
     * Get paper height.
     *
     * @return string
     */
    public function getPaperHeight();

    /**
     * Set paper size.
     *
     * @param string $width
     * @param string $height
     */
    public function setPaperSize($width, $height);

    /**
     * Set format.
     *
     * @param string $format
     */
    public function setFormat($format);

    /**
     * Get format.
     *
     * @return string
     */
    public function getFormat();

    /**
     * Set orientation.
     *
     * @param string $orientation
     */
    public function setOrientation($orientation);

    /**
     * Get orientation.
     *
     * @return string
     */
    public function getOrientation();

    /**
     * Set margin.
     *
     * @param string $margin
     */
    public function setMargin($margin);

    /**
     * Get margin.
     *
     * @return string
     */
    public function getMargin();

    /**
     * Set repeating header.
     *
     * @param string $content
     * @param string $height  (default: '1cm')
     */
    public function setRepeatingHeader($content, $height = '1cm');

    /**
     * Get repeating header.
     *
     * @return array
     */
    public function getRepeatingHeader();

    /**
     * Set repeating footer.
     *
     * @param string $content
     * @param string $height  (default: '1cm')
     */
    public function setRepeatingFooter($content, $height = '1cm');

    /**
     * Get repeating footer.
     *
     * @return array
     */
    public function getRepeatingFooter();
}

<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Http;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface PdfRequestInterface
{
    /**
     * Set paper width.
     *
     * @access public
     * @param  string $width
     * @return void
     */
    public function setPaperWidth($width);

    /**
     * Get paper width.
     *
     * @access public
     * @return string
     */
    public function getPaperWidth();

    /**
     * Set paper height.
     *
     * @access public
     * @param  string $height
     * @return void
     */
    public function setPaperHeight($height);

    /**
     * Get paper height.
     *
     * @access public
     * @return string
     */
    public function getPaperHeight();

    /**
     * Set paper size.
     *
     * @access public
     * @param  string $width
     * @param  string $height
     * @return void
     */
    public function setPaperSize($width, $height);

    /**
     * Set format.
     *
     * @access public
     * @param  string $format
     * @return void
     */
    public function setFormat($format);

    /**
     * Get format.
     *
     * @access public
     * @return string
     */
    public function getFormat();

    /**
     * Set orientation.
     *
     * @access public
     * @param  string $orientation
     * @return void
     */
    public function setOrientation($orientation);

    /**
     * Get orientation.
     *
     * @access public
     * @return string
     */
    public function getOrientation();

    /**
     * Set margin.
     *
     * @access public
     * @param  string $margin
     * @return void
     */
    public function setMargin($margin);

    /**
     * Get margin.
     *
     * @access public
     * @return string
     */
    public function getMargin();
}

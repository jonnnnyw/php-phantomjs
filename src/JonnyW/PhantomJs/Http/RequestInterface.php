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
interface RequestInterface
{
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_GET     = 'GET';
    const METHOD_HEAD    = 'HEAD';
    const METHOD_POST    = 'POST';
    const METHOD_PUT     = 'PUT';
    const METHOD_DELETE  = 'DELETE';
    const METHOD_PATCH   = 'PATCH';

    const REQUEST_TYPE_DEFAULT = 'default';
    const REQUEST_TYPE_CAPTURE = 'capture';
    const REQUEST_TYPE_PDF     = 'pdf';

    /**
     * Get request type
     *
     * @access public
     * @return string
     */
    public function getType();

    /**
     * Set request method
     *
     * @access public
     * @param string $method
     */
    public function setMethod($method);

    /**
     * Get request method
     *
     * @access public
     * @return string
     */
    public function getMethod();

    /**
     * Set timeout period
     *
     * @access public
     * @param int $timeout
     */
    public function setTimeout($timeout);

    /**
     * Get timeout period
     *
     * @access public
     * @return int
     */
    public function getTimeout();

    /**
     * Set page load delay time.
     *
     * @access public
     * @param int $delay
     */
    public function setDelay($delay);

    /**
     * Get page load delay time.
     *
     * @access public
     * @return int
     */
    public function getDelay();

    /**
     * Set viewport size.
     *
     * @access public
     * @param  int  $width
     * @param  int  $height
     * @return void
     */
    public function setViewportSize($width, $height);

    /**
     * Get viewport width.
     *
     * @access public
     * @return int
     */
    public function getViewportWidth();

    /**
     * Get viewport height.
     *
     * @access public
     * @return int
     */
    public function getViewportHeight();

    /**
     * Set request URL
     *
     * @access public
     * @param string $url
     */
    public function setUrl($url);

    /**
     * Get request URL
     *
     * @access public
     * @return string
     */
    public function getUrl();

    /**
     * Get content body
     *
     * @access public
     * @return string
     */
    public function getBody();

    /**
     * Set request data
     *
     * @access public
     * @param array $data
     */
    public function setRequestData(array $data);

    /**
     * Get request data
     *
     * @access public
     * @param  boolean $flat
     * @return array
     */
    public function getRequestData($flat = true);

    /**
     * Set headers
     *
     * @access public
     * @param array $headers
     */
    public function setHeaders(array $headers);

    /**
     * Add single header
     *
     * @access public
     * @param string $header
     * @param string $value
     */
    public function addHeader($header, $value);

    /**
     * Merge headers with existing
     *
     * @access public
     * @param array $headers
     */
    public function addHeaders(array $headers);

    /**
     * Get request headers
     *
     * @access public
     * @return array|string
     */
    public function getHeaders();

    /**
     * Get settings
     *
     * @access public
     * @return array|string
     */
    public function getSettings();

    /**
     * Get cookies
     *
     * @access public
     * @return array|string
     */
    public function getCookies();

    /**
     * Set body styles
     *
     * @access public
     * @param array $styles
     */
    public function setBodyStyles(array $styles);

    /**
     * Get body styles
     *
     * @access public
     * @return array|string
     */
    public function getBodyStyles();
}

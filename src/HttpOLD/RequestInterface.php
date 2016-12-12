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
interface RequestInterface
{
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_GET = 'GET';
    const METHOD_HEAD = 'HEAD';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH = 'PATCH';

    const REQUEST_TYPE_DEFAULT = 'default';
    const REQUEST_TYPE_CAPTURE = 'capture';
    const REQUEST_TYPE_PDF = 'pdf';

    /**
     * Get request type.
     *
     * @return string
     */
    public function getType();

    /**
     * Set request method.
     *
     * @param string $method
     */
    public function setMethod($method);

    /**
     * Get request method.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Set timeout period.
     *
     * @param int $timeout
     */
    public function setTimeout($timeout);

    /**
     * Get timeout period.
     *
     * @return int
     */
    public function getTimeout();

    /**
     * Set page load delay time.
     *
     * @param int $delay
     */
    public function setDelay($delay);

    /**
     * Get page load delay time.
     *
     * @return int
     */
    public function getDelay();

    /**
     * Set viewport size.
     *
     * @param int $width
     * @param int $height
     */
    public function setViewportSize($width, $height);

    /**
     * Get viewport width.
     *
     * @return int
     */
    public function getViewportWidth();

    /**
     * Get viewport height.
     *
     * @return int
     */
    public function getViewportHeight();

    /**
     * Set request URL.
     *
     * @param string $url
     */
    public function setUrl($url);

    /**
     * Get request URL.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Get content body.
     *
     * @return string
     */
    public function getBody();

    /**
     * Set request data.
     *
     * @param array $data
     */
    public function setRequestData(array $data);

    /**
     * Get request data.
     *
     * @param bool $flat
     *
     * @return array
     */
    public function getRequestData($flat = true);

    /**
     * Set headers.
     *
     * @param array $headers
     */
    public function setHeaders(array $headers);

    /**
     * Add single header.
     *
     * @param string $header
     * @param string $value
     */
    public function addHeader($header, $value);

    /**
     * Merge headers with existing.
     *
     * @param array $headers
     */
    public function addHeaders(array $headers);

    /**
     * Get request headers.
     *
     * @return array|string
     */
    public function getHeaders();

    /**
     * Get settings.
     *
     * @return array|string
     */
    public function getSettings();

    /**
     * Get cookies.
     *
     * @return array|string
     */
    public function getCookies();

    /**
     * Set body styles.
     *
     * @param array $styles
     */
    public function setBodyStyles(array $styles);

    /**
     * Get body styles.
     *
     * @return array|string
     */
    public function getBodyStyles();
}

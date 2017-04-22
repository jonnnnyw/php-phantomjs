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
interface ResponseInterface
{
    /**
     * Import response data
     *
     * @access public
     */
    public function import(array $data);

    /**
     * Get HTTP headers array
     *
     * @access public
     * @return array
     */
    public function getHeaders();

    /**
     * Get HTTP header value for code
     *
     * @access public
     * @param  string $code
     * @return mixed
     */
    public function getHeader($code);

    /**
     * Get response status code
     *
     * @access public
     * @return integer
     */
    public function getStatus();

    /**
     * Get page content from respone
     *
     * @access public
     * @return string
     */
    public function getContent();

    /**
     * Get content type header
     *
     * @access public
     * @return string
     */
    public function getContentType();

    /**
     * Get request URL
     *
     * @access public
     * @return string
     */
    public function getUrl();

    /**
     * Get redirect URL (if redirected)
     *
     * @access public
     * @return string
     */
    public function getRedirectUrl();

    /**
     * Is response a redirect
     *  - Checks status codes
     *
     * @access public
     * @return boolean
     */
    public function isRedirect();

    /**
     * Get time string
     *
     * @access public
     * @return string
     */
    public function getTime();

    /**
     * Get session cookies
     *
     * @access public
     * @return array
     */
    public function getCookies();
}

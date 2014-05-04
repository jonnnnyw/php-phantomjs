<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Message;

use JonnyW\PhantomJs\Exception\InvalidUrlException;
use JonnyW\PhantomJs\Exception\InvalidMethodException;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Request implements RequestInterface
{
    /**
     * Headers
     *
     * @var array
     */
    protected $headers;

    /**
     * Request data
     *
     * @var array
     */
    protected $data;

    /**
     * Request method
     *
     * @var string
     */
    protected $method;

    /**
     * Request URL
     *
     * @var string
     */
    protected $url;
    
    /**
     * Viewport width
     *
     * @var int
     */
    protected $viewportWidth;

    /**
     * Viewport height
     *
     * @var int
     */
    protected $viewportHeight;

    /**
     * Internal constructor
     *
     * @param  string $method
     * @param  string $url
     * @return void
     */
    public function __construct($method = RequestInterface::METHOD_GET, $url = null)
    {
        $this->headers  = array();
        $this->data  = array();

        $this->setMethod($method);

        if ($url) {
            $this->setUrl($url);
        }
    }

    /**
     * Set request method
     *
     * @param  string                            $method
     * @return \JonnyW\PhantomJs\Message\Request
     */
    public function setMethod($method)
    {
        $method   = strtoupper($method);
        $reflection  = new \ReflectionClass('JonnyW\PhantomJs\Message\RequestInterface');

        // Validate method
        if (!$reflection->hasConstant('METHOD_' . $method)) {
            throw new InvalidMethodException(sprintf('Invalid method provided: %s', $method));
        }

        $this->method = $method;

        return $this;
    }

    /**
     * Get request method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set request URL
     *
     * @param  string                            $url
     * @return \JonnyW\PhantomJs\Message\Request
     */
    public function setUrl($url)
    {
        // Validate URL
        if (!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
            throw new InvalidUrlException(sprintf('Invalid URL provided: %s', $url));
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Get request URL
     *  - Assembles query string for GET
     *  and HEAD requests
     *
     * @return string
     */
    public function getUrl()
    {
        if (!in_array($this->getMethod(), array(RequestInterface::METHOD_GET, RequestInterface::METHOD_HEAD))) {
            return $this->url;
        }

        $url = $this->url;

        // Add query string to URL
        if (count($this->data)) {

            $url  .= false === strpos($url, '?') ? '?' : '&';
            $url  .= urldecode(http_build_query($this->data));
        }

        return $url;
    }
    
    /**
     * Set viewport width
     *
     * @param  string $viewport_width
     * @return \JonnyW\PhantomJs\Message\Request
     */
    public function setViewportWidth($viewport_width)
    {
        // Validate width
        if (!is_numeric($viewport_width)) {
            throw new Exception('Invalid viewport width provided');
        }
    
        $this->viewportWidth = $viewport_width;
    
        return $this;
    }
    
    /**
     * Get viewport width
     *
     * @return int
     */
    public function getViewportWidth()
    {
        return $this->viewportWidth ? $this->viewportWidth : 1024;
    }
    
    /**
     * Set viewport height
     *
     * @param  string $viewport_height
     * @return \JonnyW\PhantomJs\Message\Request
     */
    public function setViewportHeight($viewport_height)
    {
        // Validate height
        if (!is_numeric($viewport_height)) {
            throw new Exception('Invalid viewport height provided');
        }
    
        $this->viewportHeight = $viewport_height;
    
        return $this;
    }
    
    /**
     * Get viewport height
     *
     * @return int
     */
    public function getViewportHeight()
    {
        return $this->viewportHeight ? $this->viewportHeight : 768;
    }

    /**
     * Get content body
     *  - Returns query string if not GET or HEAD
     *
     * @return string
     */
    public function getBody()
    {
        if (in_array($this->getMethod(), array(RequestInterface::METHOD_GET, RequestInterface::METHOD_HEAD))) {
            return '';
        }

        return urldecode(http_build_query($this->getRequestData()));
    }

    /**
     * Set request data
     *
     * @param  array                             $data
     * @return \JonnyW\PhantomJs\Message\Request
     */
    public function setRequestData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get request data
     *
     * @param  boolean $flat
     * @return array
     */
    public function getRequestData($flat = true)
    {
        if ($flat) {
            $this->flattenData($this->data);
        }

        return $this->data;
    }

    /**
     * Flatten data into single
     * dimensional array
     *
     * @param  array  $data
     * @param  string $prefix
     * @param  string $format
     * @return array
     */
    protected function flattenData(array $data, $prefix  = '', $format = '%s')
    {
        $flat = array();

        foreach ($data as $name => $value) {

            $ref = $prefix . sprintf($format, $name);

            if (is_array($value)) {

                $flat += $this->flattenData($value, $ref, '[%s]');
                continue;
            }

            $flat[$ref] = $value;
        }

        return $flat;
    }

    /**
     * Set headers
     *
     * @param  array                             $headers
     * @return \JonnyW\PhantomJs\Message\Request
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * Add single header
     *
     * @param  string                            $header
     * @param  string                            $value
     * @return \JonnyW\PhantomJs\Message\Request
     */
    public function addHeader($header, $value)
    {
        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * Merge headers with existing
     *
     * @param  array                             $headers
     * @return \JonnyW\PhantomJs\Message\Request
     */
    public function addHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Get request headers
     *
     * @param  string $format
     * @return array
     */
    public function getHeaders($format = 'default')
    {
        if ($format == 'json') {
            return json_encode($this->headers);
        }

        return $this->headers;
    }
}

<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Http;

use JonnyW\PhantomJs\Procedure\OutputInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Response
    implements ResponseInterface, OutputInterface
{
    /**
     * Http headers array
     *
     * @var array
     * @access public
     */
    public $headers;

    /**
     * Response int
     *
     * @var string
     * @access public
     */
    public $status;

    /**
     * Response body
     *
     * @var string
     * @access public
     */
    public $content;

    /**
     * Response content type header
     *
     * @var string
     * @access public
     */
    public $contentType;

    /**
     * Requested URL
     *
     * @var string
     * @access public
     */
    public $url;

    /**
     * Redirected URL
     *
     * @var string
     * @access public
     */
    public $redirectURL;

    /**
     * Request time string
     *
     * @var string
     * @access public
     */
    public $time;

    /**
     * Console messages
     *
     * @var array
     * @access public
     */
    public $console;

    /**
     * Session cookies
     *
     * @var array
     * @access public
     */
    public $cookies;

    /**
     * Import response data
     *
     * @access public
     * @param  array                           $data
     * @return \JonnyW\PhantomJs\Http\Response
     */
    public function import(array $data)
    {
        foreach ($data as $param => $value) {

            if ($param === 'headers') {
                continue;
            }

            if (property_exists($this, $param)) {
                $this->$param = $value;
            }
        }

        $this->headers = array();

        if (isset($data['headers'])) {
            $this->setHeaders((array) $data['headers']);
        }

        return $this;
    }

    /**
     * Set headers array
     *
     * @access protected
     * @param  array                           $headers
     * @return \JonnyW\PhantomJs\Http\Response
     */
    protected function setHeaders(array $headers)
    {
        foreach ($headers as $header) {

            if (isset($header['name']) && isset($header['value'])) {
                $this->headers[$header['name']] = $header['value'];
            }
        }

        return $this;
    }

    /**
     * Get HTTP headers array
     *
     * @access public
     * @return array
     */
    public function getHeaders()
    {
        return (array) $this->headers;
    }

    /**
     * Get HTTP header value for code
     *
     * @access public
     * @param  string $code
     * @return mixed
     */
    public function getHeader($code)
    {
        if (isset($this->headers[$code])) {
            return $this->headers[$code];
        }

        return null;
    }

    /**
     * Get response status code
     *
     * @access public
     * @return integer
     */
    public function getStatus()
    {
        return (int) $this->status;
    }

    /**
     * Get page content from response
     *
     * @access public
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Get content type header
     *
     * @access public
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Get request URL
     *
     * @access public
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get redirect URL (if redirected)
     *
     * @access public
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectURL;
    }

    /**
     * Is response a redirect
     *  - Checks status codes
     *
     * @access public
     * @return boolean
     */
    public function isRedirect()
    {
        $status = $this->getStatus();

        return (bool) ($status >= 300 && $status <= 307);
    }

    /**
     * Get time string
     *
     * @access public
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Get console messages
     *
     * @access public
     * @return array
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Get session cookies
     *
     * @access public
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }
}

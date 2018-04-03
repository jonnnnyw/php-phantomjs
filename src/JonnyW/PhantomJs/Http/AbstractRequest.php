<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Http;

use JonnyW\PhantomJs\Exception\InvalidMethodException;
use JonnyW\PhantomJs\Procedure\InputInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
abstract class AbstractRequest
    implements RequestInterface, InputInterface
{
    /**
     * Headers
     *
     * @var array
     * @access protected
     */
    protected $headers;

    /**
     * Settings
     *
     * @var array
     * @access protected
     */
    protected $settings;

    /**
     * Cookies
     *
     * @var array
     * @access protected
     */
    protected $cookies;

    /**
     * Request data
     *
     * @var array
     * @access protected
     */
    protected $data;

    /**
     * Request URL
     *
     * @var string
     * @access protected
     */
    protected $url;

    /**
     * Request method
     *
     * @var string
     * @access protected
     */
    protected $method;

    /**
     * Timeout period
     *
     * @var int
     * @access protected
     */
    protected $timeout;

    /**
     * Page load delay time.
     *
     * @var int
     * @access protected
     */
    protected $delay;

    /**
     * Viewport width.
     *
     * @var int
     * @access protected
     */
    protected $viewportWidth;

    /**
     * Viewport height.
     *
     * @var int
     * @access protected
     */
    protected $viewportHeight;

    /**
     * Body styles.
     *
     * @var array
     * @access protected
     */
    protected $bodyStyles;

    /**
     * Internal constructor
     *
     * @access public
     * @param string $url     (default: null)
     * @param string $method  (default: RequestInterface::METHOD_GET)
     * @param int    $timeout (default: 5000)
     */
    public function __construct($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        $this->headers         = array();
        $this->data            = array();
        $this->bodyStyles      = array();
        $this->settings        = array();
        $this->delay           = 0;
        $this->viewportWidth   = 0;
        $this->viewportHeight  = 0;

        $this->cookies = array(
            'add'    => array(),
            'delete' => array()
        );

        $this->setMethod($method);
        $this->setTimeout($timeout);

        if ($url) {
            $this->setUrl($url);
        }
    }

    /**
     * Set request method
     *
     * @access public
     * @param  string                                             $method
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     * @throws \JonnyW\PhantomJs\Exception\InvalidMethodException
     */
    public function setMethod($method)
    {
        $method     = strtoupper($method);
        $reflection = new \ReflectionClass('\JonnyW\PhantomJs\Http\RequestInterface');

        if (!$reflection->hasConstant('METHOD_' . $method)) {
            throw new InvalidMethodException(sprintf('Invalid method provided: %s', $method));
        }

        $this->method = $method;

        return $this;
    }

    /**
     * Get request method
     *
     * @access public
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set timeout period
     *
     * @access public
     * @param  int                                    $timeout
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function setTimeout($timeout)
    {
        $this->settings['resourceTimeout'] = $timeout;

        return $this;
    }

    /**
     * Get timeout period
     *
     * @access public
     * @return int
     */
    public function getTimeout()
    {
        if (isset($this->settings['resourceTimeout'])) {
            return $this->settings['resourceTimeout'];
        }

        return null;
    }

    /**
     * Set page load delay time (seconds).
     *
     * @access public
     * @param  int                                    $delay
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function setDelay($delay)
    {
        $this->delay = (int) $delay;

        return $this;
    }

    /**
     * Get page load delay time (seconds).
     *
     * @access public
     * @return int
     */
    public function getDelay()
    {
        return (int) $this->delay;
    }

    /**
     * Set viewport size.
     *
     * @access public
     * @param  int  $width
     * @param  int  $height
     * @return void
     */
    public function setViewportSize($width, $height)
    {
        $this->viewportWidth  = (int) $width;
        $this->viewportHeight = (int) $height;

        return $this;
    }

    /**
     * Get viewport width.
     *
     * @access public
     * @return int
     */
    public function getViewportWidth()
    {
        return (int) $this->viewportWidth;
    }

    /**
     * Get viewport height.
     *
     * @access public
     * @return int
     */
    public function getViewportHeight()
    {
        return (int) $this->viewportHeight;
    }

    /**
     * Set request URL
     *
     * @access public
     * @param  string                                 $url
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get request URL
     *  - Assembles query string for GET
     *  and HEAD requests
     *
     * @access public
     * @return string
     */
    public function getUrl()
    {
        if (!in_array($this->getMethod(), array(RequestInterface::METHOD_GET, RequestInterface::METHOD_HEAD))) {
            return $this->url;
        }

        $url = $this->url;

        if (count($this->data)) {

            $url .= false === strpos($url, '?') ? '?' : '&';
            $url .= http_build_query($this->data);
        }

        return $url;
    }

    /**
     * Get content body
     *  - Returns query string if not GET or HEAD
     *
     * @access public
     * @return string
     */
    public function getBody()
    {
        if (in_array($this->getMethod(), array(RequestInterface::METHOD_GET, RequestInterface::METHOD_HEAD))) {
            return '';
        }

        return http_build_query($this->getRequestData());
    }

    /**
     * Set request data
     *
     * @access public
     * @param  array                                  $data
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function setRequestData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get request data
     *
     * @access public
     * @param  boolean $flat
     * @return array
     */
    public function getRequestData($flat = true)
    {
        if ($flat) {
            return $this->flattenData($this->data);
        }

        return $this->data;
    }

    /**
     * Set headers
     *
     * @access public
     * @param  array                                  $headers
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * Add single header
     *
     * @access public
     * @param  string                                 $header
     * @param  string                                 $value
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function addHeader($header, $value)
    {
        $this->headers[$header] = $value;

        return $this;
    }

    /**
     * Merge headers with existing
     *
     * @access public
     * @param  array                                  $headers
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function addHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Get request headers
     *
     * @access public
     * @param  string       $format
     * @return array|string
     */
    public function getHeaders($format = 'default')
    {
        if ($format === 'json') {
            return json_encode($this->headers);
        }

        return $this->headers;
    }

    /**
     * Add single setting
     *
     * @access public
     * @param  string                                 $setting
     * @param  string                                 $value
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function addSetting($setting, $value)
    {
        $this->settings[$setting] = $value;

        return $this;
    }

    /**
     * Get settings
     *
     * @access public
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Add cookie.
     *
     * @access public
     * @param  string                                 $name
     * @param  mixed                                  $value
     * @param  string                                 $path
     * @param  string                                 $domain
     * @param  bool                                   $httpOnly (default: true)
     * @param  bool                                   $secure   (default: false)
     * @param  int                                    $expires  (default: null)
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function addCookie($name, $value, $path, $domain, $httpOnly = true, $secure = false, $expires = null)
    {
        $filter = function ($value) {
            return !is_null($value);
        };

        $this->cookies['add'][] = array_filter(array(
            'name'     => $name,
            'value'    => $value,
            'path'     => $path,
            'domain'   => $domain,
            'httponly' => $httpOnly,
            'secure'   => $secure,
            'expires'  => $expires
        ), $filter);

        return $this;
    }

    /**
     * Delete cookie.
     *
     * @access public
     * @param  string                                 $name
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function deleteCookie($name)
    {
        $this->cookies['delete'][] = $name;

        return $this;
    }

    /**
     * Get cookies
     *
     * @access public
     * @return array
     */
    public function getCookies()
    {
        return $this->cookies;
    }

    /**
     * Set body styles
     *
     * @access public
     * @param  array                                  $styles
     * @return \JonnyW\PhantomJs\Http\AbstractRequest
     */
    public function setBodyStyles(array $styles)
    {
        $this->bodyStyles = $styles;

        return $this;
    }

    /**
     * Get body styles
     *
     * @access public
     * @param  string       $format (default: 'default')
     * @return array|string
     */
    public function getBodyStyles($format = 'default')
    {
        if ($format === 'json') {
            return json_encode($this->bodyStyles);
        }

        return $this->bodyStyles;
    }

    /**
     * Flatten data into single
     * dimensional array
     *
     * @access protected
     * @param  array  $data
     * @param  string $prefix
     * @param  string $format
     * @return array
     */
    protected function flattenData(array $data, $prefix = '', $format = '%s')
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
}

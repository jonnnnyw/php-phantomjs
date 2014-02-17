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
interface RequestInterface
{
	const METHOD_OPTIONS = 'OPTIONS';
	const METHOD_GET     = 'GET';
	const METHOD_HEAD    = 'HEAD';
	const METHOD_POST    = 'POST';
	const METHOD_PUT     = 'PUT';
	const METHOD_DELETE  = 'DELETE';
	const METHOD_PATCH   = 'PATCH';

	/**
	 * Set request method
	 *
	 * @param string $method
	 * @return Request
	 */
	public function setMethod($method);

	/**
	 * Get request method
	 *
	 * @return string
	 */
	public function getMethod();

	/**
	 * Set request URL
	 *
	 * @param string $url
	 * @return Request
	 */
	public function setUrl($url);

	/**
	 * Get request URL
	 *  - Assembles query string for GET
	 *  and HEAD requests
	 *
	 * @return string
	 */
	public function getUrl();

	/**
	 * Get content body
	 *  - Returns query string if not GET or HEAD
	 *
	 * @return string
	 */
	public function getBody();

	/**
	 * Set request data
	 *
	 * @param array $data
	 * @return Request
	 */
	public function setRequestData(array $data);

	/**
	 * Get request data
	 *
	 * @param boolean $flat
	 * @return array
	 */
	public function getRequestData($flat = true);

	/**
	 * Set headers
	 *
	 * @param array $headers
	 * @return JonnyW\PhantomJs\Message\Request|null
	 */
	public function setHeaders(array $headers);

	/**
	 * Add single header
	 *
	 * @param string $header
	 * @param string $value
	 * @return Request
	 */
	public function addHeader($header, $value);

	/**
	 * Merge headers with existing
	 *
	 * @param array $headers
	 * @return Request
	 */
	public function addHeaders(array $headers);

	/**
	 * Get request headers
	 *
	 * @param string $format
	 * @return array
	 */
	public function getHeaders($format = 'default');
}
<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Message;

use JonnyW\PhantomJs\Message\ResponseInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Response implements ResponseInterface
{
	/**
	 * Http headers array
	 *
	 * @var array
	 */
	protected $headers;

	/**
	 * Response int
	 *
	 * @var string
	 */
	protected $status;

	/**
	 * Response body
	 *
	 * @var string
	 */
	protected $content;

	/**
	 * Response content type header
	 *
	 * @var string
	 */
	protected $contentType;

	/**
	 * Requested URL
	 *
	 * @var string
	 */
	protected $url;

	/**
	 * Redirected URL
	 *
	 * @var string
	 */
	protected $redirectUrl;

	/**
	 * Request time string
	 *
	 * @var string
	 */
	protected $time;

	/**
	 * Set response data
	 *
	 * @return Response
	 */
	public function setData(array $data)
	{
		$this->headers = array();

		// Set headers array
		if(isset($data['headers'])) {
			$this->setHeaders((array) $data['headers']);
		}

		// Set status
		if(isset($data['status'])) {
			$this->status = $data['status'];
		}

		// Set content
		if(isset($data['content'])) {
			$this->content = $data['content'];
		}

		// Set content type string
		if(isset($data['contentType'])) {
			$this->contentType = $data['contentType'];
		}

		// Set request URL
		if(isset($data['url'])) {
			$this->url = $data['url'];
		}

		// Set redirect URL
		if(isset($data['redirectURL'])) {
			$this->redirectUrl = $data['redirectURL'];
		}

		// Set time string
		if(isset($data['time'])) {
			$this->time = $data['time'];
		}

		return $this;
	}

	/**
	 * Set headers array
	 *
	 * @param array $headers
	 * @return
	 */
	protected function setHeaders(array $headers)
	{
		foreach($headers as $header) {

			if(isset($header['name']) && isset($header['value'])) {
				$this->headers[$header['name']] = $header['value'];
			}
		}

		return $this;
	}

	/**
	 * Get HTTP headers array
	 *
	 * @return array
	 */
	public function getHeaders()
	{
		return (array) $this->headers;
	}

	/**
	 * Get HTTP header value for code
	 *
	 * @praam string $$code
	 * @return mixed
	 */
	public function getHeader($code)
	{
		if(isset($this->headers[$code])) {
			return $this->headers[$code];
		}

		return null;
	}

	/**
	 * Get response status code
	 *
	 * @return integer
	 */
	public function getStatus()
	{
		return (int) $this->status;
	}

	/**
	 * Get page content from respone
	 *
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Get content type header
	 *
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * Get request URL
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Get redirect URL (if redirected)
	 *
	 * @return string
	 */
	public function getRedirectUrl()
	{
		return $this->redirectUrl;
	}

	/**
	 * Is response a redirect
	 *  - Checks status codes
	 *
	 * @return boolean
	 */
	public function isRedirect()
	{
		$status = $this->getStatus();

		return (bool) ($status >= 300 && $status < 307);
	}

	/**
	 * Get time string
	 *
	 * @return string
	 */
	public function getTime()
	{
		return $this->time;
	}
}
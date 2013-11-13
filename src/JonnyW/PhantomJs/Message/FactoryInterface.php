<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Message;

use  JonnyW\PhantomJs\Message\RequestInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface FactoryInterface
{
	/**
	 * Get singleton instance
	 *
	 * @return JonnyW\PhantomJs\Message\FactoryInterface
	 */
	public static function getInstance();

	/**
	 * Create request instance
	 *
	 * @param string $url
	 * @param string $method
	 * @return JonnyW\PhantomJs\Message\Request
	 */
	public function createRequest($url, $method = RequestInterface::METHOD_GET);

	/**
	 * Create response instance
	 *
	 * @return JonnyW\PhantomJs\Message\Response
	 */
	public function createResponse();
}
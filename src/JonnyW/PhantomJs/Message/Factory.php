<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Message;

use  JonnyW\PhantomJs\Message\FactoryInterface;
use  JonnyW\PhantomJs\Message\RequestInterface;
use  JonnyW\PhantomJs\Message\Request;
use  JonnyW\PhantomJs\Message\Response;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Factory implements FactoryInterface
{
	/**
	 * Client instance
	 *
	 * @var JonnyW\PhantomJs\Message\FactoryInterface
	 */
	private static $instance;

	/**
	 * Get singleton instance
	 *
	 * @return JonnyW\PhantomJs\Message\FactoryInterface
	 */
	public static function getInstance()
	{
		if(!self::$instance instanceof FactoryInterface) {
			self::$instance = new Factory();
		}

		return self::$instance;
	}

	/**
	 * Create request instance
	 *
	 * @param string $method
	 * @param string $url
	 * @return JonnyW\PhantomJs\Message\Request
	 */
	public function createRequest($method = RequestInterface::METHOD_GET, $url = null)
	{
		return new Request($method, $url);
	}

	/**
	 * Create response instance
	 *
	 * @return JonnyW\PhantomJs\Message\Response
	 */
	public function createResponse()
	{
		return new Response();
	}
}
<?php

/*
 * This file is part of the php-phantomjs.
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface ClientInterface
{
	
	/**
	 * Open page and return HTML
	 *
	 * @param string $url
	 * @return string
	 */
	public function open($url);
	
	/**
	 * Screen capture URL
	 *
	 * @param string $url
	 * @pram string $file
	 * @return string
	 */
	public function capture($url, $file);
	
	/**
	 * Set new PhantomJs path
	 *
	 * @param string $path
	 * @return JonnyW\PhantomJs\ClientInterface
	 */
	public function setPhantomJs($path);
}
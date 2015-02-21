<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs;

use JonnyW\PhantomJs\Http\RequestInterface;
use JonnyW\PhantomJs\Http\ResponseInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface ClientInterface
{
    /**
     * Get singleton instance
     *
     * @access public
     * @return \JonnyW\PhantomJs\ClientInterface
     */
    public static function getInstance();

    /**
     * Get message factory instance
     *
     * @access public
     * @return \JonnyW\PhantomJs\Http\MessageFactoryInterface
     */
    public function getMessageFactory();

    /**
     * Send request
     *
     * @access public
     * @param \JonnyW\PhantomJs\Http\RequestInterface  $request
     * @param \JonnyW\PhantomJs\Http\ResponseInterface $response
     */
    public function send(RequestInterface $request, ResponseInterface $response);

    /**
     * Get PhantomJs run command with
     * loader and run options.
     *
     * @access public
     * @return string
     */
    public function getCommand();

    /**
     * Set new PhantomJs executable path.
     *
     * @access public
     * @param string $path
     */
    public function setPhantomJs($path);

    /**
     * Get PhantomJs executable path.
     *
     * @access public
     * @return string
     */
    public function getPhantomJs();

    /**
     * Set PhantomJs run options.
     *
     * @access public
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * Get PhantomJs run options.
     *
     * @access public
     * @return array
     */
    public function getOptions();

    /**
     * Add single PhantomJs run option.
     *
     * @access public
     * @param string $option
     */
    public function addOption($option);

    /**
     * Debug.
     *
     * @access public
     * @param boolean $doDebug
     */
    public function debug($doDebug);

    /**
     * Log info.
     *
     * @access public
     * @param  string                   $info
     * @return \JonnyW\PhantomJs\Client
     */
    public function log($info);

    /**
     * Get log info.
     *
     * @access public
     * @return string
     */
    public function getLog();

    /**
     * Clear log info.
     *
     * @access public
     * @return \JonnyW\PhantomJs\Client
     */
    public function clearLog();
}

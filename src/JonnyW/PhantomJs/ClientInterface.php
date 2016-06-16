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
     * Get engine instance.
     *
     * @access public
     * @return \JonnyW\PhantomJs\Engine
     */
    public function getEngine();

    /**
     * Get message factory instance
     *
     * @access public
     * @return \JonnyW\PhantomJs\Http\MessageFactoryInterface
     */
    public function getMessageFactory();

    /**
     * Get procedure loader instance
     *
     * @access public
     * @return \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    public function getProcedureLoader();

    /**
     * Send request
     *
     * @access public
     * @param \JonnyW\PhantomJs\Http\RequestInterface  $request
     * @param \JonnyW\PhantomJs\Http\ResponseInterface $response
     */
    public function send(RequestInterface $request, ResponseInterface $response);

    /**
     * Get log.
     *
     * @access public
     * @return string
     */
    public function getLog();

    /**
     * Set procedure template.
     *
     * @access public
     * @param  string $procedure
     * @return void
     */
    public function setProcedure($procedure);

    /**
     * Get procedure template.
     *
     * @access public
     * @return string
     */
    public function getProcedure();

    /**
     * Set lazy request flag.
     *
     * @access public
     * @return void
     */
    public function isLazy();
}

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
interface MessageFactoryInterface
{
    /**
     * Get singleton instance.
     *
     * @access public
     * @return \JonnyW\PhantomJs\Message\MessageFactoryInterface
     */
    public static function getInstance();

    /**
     * Create request instance.
     *
     * @access public
     * @param  string                                     $url     (default: null)
     * @param  string                                     $method  (default: RequestInterface::METHOD_GET)
     * @param  int                                        $timeout (default: 5000)
     * @return \JonnyW\PhantomJs\Message\RequestInterface
     */
    public function createRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 30000);

    /**
     * Create capture request instance.
     *
     * @access public
     * @param  string                                     $url     (default: null)
     * @param  string                                     $method  (default: RequestInterface::METHOD_GET)
     * @param  int                                        $timeout (default: 5000)
     * @return \JonnyW\PhantomJs\Message\RequestInterface
     */
    public function createCaptureRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 30000);

    /**
     * Create response instance.
     *
     * @access public
     * @return \JonnyW\PhantomJs\Message\ResponseInterface
     */
    public function createResponse();
}

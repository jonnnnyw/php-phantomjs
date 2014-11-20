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
class MessageFactory implements MessageFactoryInterface
{
    /**
     * Client instance
     *
     * @var \JonnyW\PhantomJs\Message\MessageFactory
     * @access private
     */
    private static $instance;

    /**
     * Get singleton instance.
     *
     * @access public
     * @return \JonnyW\PhantomJs\Message\MessageFactory
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof MessageFactoryInterface) {
            self::$instance = new MessageFactory();
        }

        return self::$instance;
    }

    /**
     * Create request instance.
     *
     * @access public
     * @param  string                            $url
     * @param  string                            $method
     * @param  int                               $timeout
     * @return \JonnyW\PhantomJs\Message\Request
     */
    public function createRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 30000)
    {
        return new Request($url, $method, $timeout);
    }

    /**
     * Create capture request instance.
     *
     * @access public
     * @param  string                            $url
     * @param  string                            $method
     * @param  int                               $timeout
     * @return \JonnyW\PhantomJs\Message\Request
     */
    public function createCaptureRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 30000)
    {
        return new CaptureRequest($url, $method, $timeout);
    }

    /**
     * Create response instance.
     *
     * @access public
     * @return \JonnyW\PhantomJs\Message\Response
     */
    public function createResponse()
    {
        return new Response();
    }
}

<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Http;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class MessageFactory implements MessageFactoryInterface
{
    /**
     * Internal constructor.
     */
    public function __construct()
    {
        @trigger_error(__CLASS__.' is deprecated since version 4.6 and will be removed in 5.0.', E_USER_DEPRECATED);
    }

    /**
     * Get singleton instance.
     *
     * @return \JonnyW\PhantomJs\Http\MessageFactory
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof MessageFactoryInterface) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Create request instance.
     *
     * @param string $url
     * @param string $method
     * @param int    $timeout
     *
     * @return \JonnyW\PhantomJs\Http\Request
     */
    public function createRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        return new Request($url, $method, $timeout);
    }

    /**
     * Create capture request instance.
     *
     * @param string $url
     * @param string $method
     * @param int    $timeout
     *
     * @return \JonnyW\PhantomJs\Http\CaptureRequest
     */
    public function createCaptureRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        return new CaptureRequest($url, $method, $timeout);
    }

    /**
     * Create PDF request instance.
     *
     * @param string $url
     * @param string $method
     * @param int    $timeout
     *
     * @return \JonnyW\PhantomJs\Http\PdfRequest
     */
    public function createPdfRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        return new PdfRequest($url, $method, $timeout);
    }

    /**
     * Create response instance.
     *
     * @return \JonnyW\PhantomJs\Http\Response
     */
    public function createResponse()
    {
        return new Response();
    }
}

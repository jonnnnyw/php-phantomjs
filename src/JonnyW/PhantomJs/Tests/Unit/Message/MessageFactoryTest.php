<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Message;

use JonnyW\PhantomJs\Message\MessageFactory;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class MessageFactoryTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test get instance returns instance of
     * message factory.
     *
     * @access public
     * @return void
     */
    public function testGetInstanceReturnsInstanceOfMessageFactory()
    {
        $this->assertInstanceOf('\JonnyW\PhantomJs\Message\MessageFactory', MessageFactory::getInstance());
    }

    /**
     * Test create request returns instance of request.
     *
     * @access public
     * @return void
     */
    public function testCreateRequestReturnsInstanceOfRequest()
    {
        $messageFactory = $this->getMessageFactory();

        $this->assertInstanceOf('\JonnyW\PhantomJs\Message\Request', $messageFactory->createRequest());
    }

    /**
     * Test create request with URL sets
     * URL in request.
     *
     * @access public
     * @return void
     */
    public function testCreateRequestWithUrlSetsUrlInRequest()
    {
        $url = 'http://test.com';

        $messageFactory = $this->getMessageFactory();
        $request        = $messageFactory->createRequest($url);

        $this->assertSame($url, $request->getUrl());
    }

    /**
     * Test create request with method sets
     * method in request.
     *
     * @access public
     * @return void
     */
    public function testCreateRequestWithMethodSetsMethodInRequest()
    {
        $method = 'POST';

        $messageFactory = $this->getMessageFactory();
        $request        = $messageFactory->createRequest(null, $method);

        $this->assertSame($method, $request->getMethod());
    }

    /**
     * Test create request with timeout sets
     * timeout in request.
     *
     * @access public
     * @return void
     */
    public function testCreateRequestWithTimeoutSetsTimeoutInRequest()
    {
        $timeout = 123456789;

        $messageFactory = $this->getMessageFactory();
        $request        = $messageFactory->createRequest(null, 'GET', $timeout);

        $this->assertSame($timeout, $request->getTimeout());
    }

    /**
     * Test create capture request returns
     * instance of capture request.
     *
     * @access public
     * @return void
     */
    public function testCreateCaptureRequestReturnsInstanceOfCaptureRequest()
    {
        $messageFactory = $this->getMessageFactory();

        $this->assertInstanceOf('\JonnyW\PhantomJs\Message\CaptureRequest', $messageFactory->createCaptureRequest());
    }

    /**
     * Test create capture request with URL sets
     * URL in capture request.
     *
     * @access public
     * @return void
     */
    public function testCreateCaptureRequestWithUrlSetsUrlInCaptureRequest()
    {
        $url = 'http://test.com';

        $messageFactory = $this->getMessageFactory();
        $captureRequest = $messageFactory->createCaptureRequest($url);

        $this->assertSame($url, $captureRequest->getUrl());
    }

    /**
     * Test create capture request with method sets
     * method in capture request.
     *
     * @access public
     * @return void
     */
    public function testCreateCaptureRequestWithMethodSetsMethodInCaptureRequest()
    {
        $method = 'POST';

        $messageFactory = $this->getMessageFactory();
        $captureRequest = $messageFactory->createCaptureRequest(null, $method);

        $this->assertSame($method, $captureRequest->getMethod());
    }

    /**
     * Test create capture request with timeout sets
     * timeout in capture request.
     *
     * @access public
     * @return void
     */
    public function testCreateCaptureRequestWithTimeoutSetsTimeoutInCaptureRequest()
    {
        $timeout = 123456789;

        $messageFactory = $this->getMessageFactory();
        $captureRequest = $messageFactory->createCaptureRequest(null, 'GET', $timeout);

        $this->assertSame($timeout, $captureRequest->getTimeout());
    }

    /**
     * Test create response returns instance of response.
     *
     * @access public
     * @return void
     */
    public function testCreateResponseReturnsInstanceOfResponse()
    {
        $messageFactory = $this->getMessageFactory();

        $this->assertInstanceOf('\JonnyW\PhantomJs\Message\Response', $messageFactory->createResponse());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get message factory instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Message\MessageFactory
     */
    protected function getMessageFactory()
    {
        $messageFactory = new MessageFactory();

        return $messageFactory;
    }
}

<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Http;

use JonnyW\PhantomJs\Http\MessageFactory;

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
     * Test factory method creates message factory.
     *
     * @access public
     * @return void
     */
    public function testFactoryMethodCreatesMessageFactory()
    {
        $this->assertInstanceOf('\JonnyW\PhantomJs\Http\MessageFactory', MessageFactory::getInstance());
    }

    /**
     * Test can create request.
     *
     * @access public
     * @return void
     */
    public function testCanCreateRequest()
    {
        $messageFactory = $this->getMessageFactory();

        $this->assertInstanceOf('\JonnyW\PhantomJs\Http\Request', $messageFactory->createRequest());
    }

    /**
     * Test can create request with URL.
     *
     * @access public
     * @return void
     */
    public function testCanCreateRequestWithUrl()
    {
        $url = 'http://test.com';

        $messageFactory = $this->getMessageFactory();
        $request        = $messageFactory->createRequest($url);

        $this->assertEquals($url, $request->getUrl());
    }

    /**
     * Test can create request with method.
     *
     * @access public
     * @return void
     */
    public function testCanCreateRequestWithMethod()
    {
        $method = 'POST';

        $messageFactory = $this->getMessageFactory();
        $request        = $messageFactory->createRequest(null, $method);

        $this->assertEquals($method, $request->getMethod());
    }

    /**
     * Test can create request with timeout.
     *
     * @access public
     * @return void
     */
    public function testCanCreateRequestWithTimeout()
    {
        $timeout = 123456789;

        $messageFactory = $this->getMessageFactory();
        $request        = $messageFactory->createRequest(null, 'GET', $timeout);

        $this->assertEquals($timeout, $request->getTimeout());
    }

    /**
     * Test can create capture request.
     *
     * @access public
     * @return void
     */
    public function testCanCreateCaptureRequest()
    {
        $messageFactory = $this->getMessageFactory();

        $this->assertInstanceOf('\JonnyW\PhantomJs\Http\CaptureRequest', $messageFactory->createCaptureRequest());
    }

    /**
     * Test can create capture request with URL.
     *
     * @access public
     * @return void
     */
    public function testCanCreateCaptureRequestWithUrl()
    {
        $url = 'http://test.com';

        $messageFactory = $this->getMessageFactory();
        $captureRequest = $messageFactory->createCaptureRequest($url);

        $this->assertEquals($url, $captureRequest->getUrl());
    }

    /**
     * Test can create capture request
     * with method.
     *
     * @access public
     * @return void
     */
    public function testCanCreateCaptureRequestWithMethod()
    {
        $method = 'POST';

        $messageFactory = $this->getMessageFactory();
        $captureRequest = $messageFactory->createCaptureRequest(null, $method);

        $this->assertEquals($method, $captureRequest->getMethod());
    }

    /**
     * Test can create capture request with timeout.
     *
     * @access public
     * @return void
     */
    public function testCanCreateCaptureRequestWithTimeout()
    {
        $timeout = 123456789;

        $messageFactory = $this->getMessageFactory();
        $captureRequest = $messageFactory->createCaptureRequest(null, 'GET', $timeout);

        $this->assertEquals($timeout, $captureRequest->getTimeout());
    }

    /**
     * Test can create response.
     *
     * @access public
     * @return void
     */
    public function testCanCreateResponse()
    {
        $messageFactory = $this->getMessageFactory();

        $this->assertInstanceOf('\JonnyW\PhantomJs\Http\Response', $messageFactory->createResponse());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get message factory instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Http\MessageFactory
     */
    protected function getMessageFactory()
    {
        $messageFactory = new MessageFactory();

        return $messageFactory;
    }
}

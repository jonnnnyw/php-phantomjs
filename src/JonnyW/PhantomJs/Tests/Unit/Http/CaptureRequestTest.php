<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Http;

use JonnyW\PhantomJs\Http\CaptureRequest;
use JonnyW\PhantomJs\Http\RequestInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class CaptureRequestTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test capture type is returned by default
     * if no type is set.
     *
     * @access public
     * @return void
     */
    public function testCaptureTypeIsReturnedByDefaultIfNotTypeIsSet()
    {
        $captureRequest = $this->getCaptureRequest();

        $this->assertEquals(RequestInterface::REQUEST_TYPE_CAPTURE, $captureRequest->getType());
    }

    /**
     * Test custom type can be set.
     *
     * @access public
     * @return void
     */
    public function testCustomTypeCanBeSet()
    {
        $requestType = 'testType';

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setType($requestType);

        $this->assertEquals($requestType, $captureRequest->getType());
    }

    /**
     * Test URL can be set via constructor.
     *
     * @access public
     * @return void
     */
    public function testUrlCanBeSetViaConstructor()
    {
        $url            = 'http://test.com';
        $captureRequest = $this->getCaptureRequest($url);

        $this->assertEquals($url, $captureRequest->getUrl());
    }

    /**
     * Test method can be set via constructor.
     *
     * @access public
     * @return void
     */
    public function testMethodCanBeSetViaConstructor()
    {
        $method         = 'GET';
        $captureRequest = $this->getCaptureRequest(null, $method);

        $this->assertEquals($method, $captureRequest->getMethod());
    }

    /**
     * Test timeout can be set via constructor.
     *
     * @access public
     * @return void
     */
    public function testTimeoutCanBeSetViaConstructor()
    {
        $timeout        = 100000;
        $captureRequest = $this->getCaptureRequest('http://test.com', 'GET', $timeout);

        $this->assertEquals($timeout, $captureRequest->getTimeout());
    }

    /**
     * Test invalid method is thrown if method
     * is invalid.
     *
     * @access public
     * @return void
     */
    public function testInvalidMethodIsThrownIfMethodIsInvalid()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\InvalidMethodException');

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('INVALID_METHOD');
    }

    /**
     * Test rect width can be set.
     *
     * @access public
     * @return void
     */
    public function testRectWidthCanBeSet()
    {
        $width  = 100;
        $height = 200;

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setCaptureDimensions($width, $height);

        $this->assertEquals($width, $captureRequest->getRectWidth());
    }

    /**
     * Test rect height can be set.
     *
     * @access public
     * @return void
     */
    public function testRectHeightCanBeSet()
    {
        $width  = 100;
        $height = 200;

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setCaptureDimensions($width, $height);

        $this->assertEquals($height, $captureRequest->getRectHeight());
    }

    /**
     * Test rect top can be set.
     *
     * @access public
     * @return void
     */
    public function testRectTopCanBeSet()
    {
        $width  = 100;
        $height = 200;
        $top    = 50;

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setCaptureDimensions($width, $height, $top);

        $this->assertEquals($top, $captureRequest->getRectTop());
    }

    /**
     * Test rect left can be set.
     *
     * @access public
     * @return void
     */
    public function testRectLeftCanBeSet()
    {
        $width  = 100;
        $height = 200;
        $left   = 50;

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setCaptureDimensions($width, $height, 0, $left);

        $this->assertEquals($left, $captureRequest->getRectLeft());
    }

    /**
     * Test URL does not contain query params if
     * mehtod is not HEAD or GET.
     *
     * @access public
     * @return void
     */
    public function testUrlDoesNotContainQueryParamsIfMethodIsNotHeadOrGet()
    {
        $url = 'http://test.com';

        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => 'Testing2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('POST');
        $captureRequest->setUrl($url);
        $captureRequest->setRequestData($data);

        $this->assertEquals($url, $captureRequest->getUrl());
    }

    /**
     * Test URL does contain query params if mehthod
     * is GET.
     *
     * @access public
     * @return void
     */
    public function testUrlDoesContainQueryParamsIfMethodIsGet()
    {
        $url = 'http://test.com';

        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => 'Testing2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('GET');
        $captureRequest->setUrl($url);
        $captureRequest->setRequestData($data);

        $expectedUrl = $url . '?test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($expectedUrl, $captureRequest->getUrl());
    }

    /**
     * Test URL does contain query params if method
     * is HEAD.
     *
     * @access public
     * @return void
     */
    public function testUrlDoesContainQueryParamsIfMethodIsHead()
    {
        $url = 'http://test.com';

        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => 'Testing2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('HEAD');
        $captureRequest->setUrl($url);
        $captureRequest->setRequestData($data);

        $expectedUrl = $url . '?test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($expectedUrl, $captureRequest->getUrl());
    }

    /**
     * Test query params are appended to URL if
     * URL contains existng query params.
     *
     * @access public
     * @return void
     */
    public function testQueryParamsAreAppendedToUrlIfUrlContainsExistingQueryParams()
    {
        $url = 'http://test.com?existing_param=Existing';

        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => 'Testing2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('GET');
        $captureRequest->setUrl($url);
        $captureRequest->setRequestData($data);

        $expectedUrl = $url . '&test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($expectedUrl, $captureRequest->getUrl());
    }

    /**
     * Test request contains no body if method
     * is GET.
     *
     * @access public
     * @return void
     */
    public function testRequestContainsNoBodyIfMethodIsGet()
    {
        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => 'Testing2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('GET');
        $captureRequest->setRequestData($data);

        $this->assertEquals('', $captureRequest->getBody());
    }

    /**
     * Test request contains no body if method
     * is HEAD.
     *
     * @access public
     * @return void
     */
    public function testRequestContainsNoBodyIfMethodIsHead()
    {
        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => 'Testing2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('HEAD');
        $captureRequest->setRequestData($data);

        $this->assertEquals('', $captureRequest->getBody());
    }

    /**
     * Test request contains a body if method is
     * not HEAD or GET.
     *
     * @access public
     * @return void
     */
    public function testRequestContainsABodyIfMethodIsNotHeadOrGet()
    {
        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => 'Testing2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('POST');
        $captureRequest->setRequestData($data);

        $body = 'test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($body, $captureRequest->getBody());
    }

    /**
     * Test request data can be flattened.
     *
     * @access public
     * @return void
     */
    public function testRequestDataCanBeFalttened()
    {
        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => array(
                'Testing2',
                'Testing3'
            )
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setRequestData($data);

        $flatData = array(
            'test_param1'    => 'Testing1',
            'test_param2[0]' => 'Testing2',
            'test_param2[1]' => 'Testing3'
        );

        $this->assertEquals($flatData, $captureRequest->getRequestData(true));
    }

    /**
     * Test raw request data can be accessed.
     *
     * @access public
     * @return void
     */
    public function testRawRequestDataCanBeAccessed()
    {
        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => array(
                'Testing2',
                'Testing3'
            )
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setRequestData($data);

        $this->assertEquals($data, $captureRequest->getRequestData(false));
    }

    /**
     * Test headers can be added.
     *
     * @access public
     * @return void
     */
    public function testHeadersCanBeAdded()
    {
        $existingHeaders = array(
            'Header1' => 'Header 1'
        );

        $newHeaders = array(
            'Header2' => 'Header 2',
            'Header3' => 'Header 3'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setHeaders($existingHeaders);
        $captureRequest->addHeaders($newHeaders);

        $expectedHeaders = array_merge($existingHeaders, $newHeaders);

        $this->assertEquals($expectedHeaders, $captureRequest->getHeaders());
    }

    /**
     * Test headers can be accessed in
     * JSON format
     *
     * @access public
     * @return void
     */
    public function testHeadersCanBeAccessedInJsonFormat()
    {
        $headers = array(
            'Header1' => 'Header 1',
            'Header2' => 'Header 2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setHeaders($headers);

        $expectedHeaders = json_encode($headers);

        $this->assertEquals($expectedHeaders, $captureRequest->getHeaders('json'));
    }

    /**
     * Test raw headers can be accessed.
     *
     * @access public
     * @return void
     */
    public function testRawHeadersCanBeAccessed()
    {
        $headers = array(
            'Header1' => 'Header 1',
            'Header2' => 'Header 2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setHeaders($headers);

        $this->assertEquals($headers, $captureRequest->getHeaders('default'));
    }

    /**
     * Test not writable exception is thrown if
     * output path is not writable.
     *
     * @access public
     * @return void
     */
    public function testNotWritableExceptonIsThrownIfOutputPathIsNotWritable()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotWritableException');

        $invalidPath = '/invalid/path';

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setOutputFile($invalidPath);
    }

    /**
     * Test can set output file.
     *
     * @access public
     * @return void
     */
    public function testCanSetOutputFile()
    {
        $outputFile = sprintf('%s/test.jpg', sys_get_temp_dir());

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setOutputFile($outputFile);

        $this->assertEquals($outputFile, $captureRequest->getOutputFile());
    }

    /**
     * Test can set viewport width.
     *
     * @access public
     * @return void
     */
    public function testCanSetViewportWidth()
    {
        $width  = 100;
        $height = 200;

        $caputreRequest = $this->getCaptureRequest();
        $caputreRequest->setViewportSize($width, $height);

        $this->assertEquals($width, $caputreRequest->getViewportWidth());
    }

    /**
     * Test can set viewport height.
     *
     * @access public
     * @return void
     */
    public function testCanSetViewportHeight()
    {
        $width  = 100;
        $height = 200;

        $caputreRequest = $this->getCaptureRequest();
        $caputreRequest->setViewportSize($width, $height);

        $this->assertEquals($height, $caputreRequest->getViewportHeight());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get capture request instance.
     *
     * @access protected
     * @param  string                                $url     (default: null)
     * @param  string                                $method  (default: RequestInterface::METHOD_GET)
     * @param  int                                   $timeout (default: 5000)
     * @return \JonnyW\PhantomJs\Http\CaptureRequest
     */
    protected function getCaptureRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        $captureRequest = new CaptureRequest($url, $method, $timeout);

        return $captureRequest;
    }
}

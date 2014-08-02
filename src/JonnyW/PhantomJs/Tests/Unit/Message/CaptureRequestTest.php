<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Message;

use JonnyW\PhantomJs\Message\CaptureRequest;
use JonnyW\PhantomJs\Message\RequestInterface;

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
     * Test get type returns capture request type.
     *
     * @access public
     * @return void
     */
    public function testGetTypeReturnsCaptureRequestType()
    {
        $captureRequest = $this->getCaptureRequest();

        $this->assertSame(RequestInterface::REQUEST_TYPE_CAPTURE, $captureRequest->getType());
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

        $this->assertSame($url, $captureRequest->getUrl());
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

        $this->assertSame($method, $captureRequest->getMethod());
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

        $this->assertSame($timeout, $captureRequest->getTimeout());
    }

    /**
     * Test set method throws invalid method
     * exception if an invalid method is set
     *
     * @access public
     * @return void
     */
    public function testSetMethodThrowsInvalidMethodExceptionIfAnInvalidMethodIsSet()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\InvalidMethodException');

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('INVALID_METHOD');
    }

  /**
     * Test set method throws invalid method
     * exception if an invalid method is set
     * via constructor
     *
     * @access public
     * @return void
     */
    public function testSetMethodThrowsInvalidMethodExceptionIfAnInvalidMethodIsSetViaConstructor()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\InvalidMethodException');

        $this->getCaptureRequest('http://test.com', 'INVALID_METHOD');
    }

    /**
     * Test set capture dimensions sets
     * rect width.
     *
     * @access public
     * @return void
     */
    public function testSetCaptureDimensionsSetsRectWidth()
    {
        $width  = 100;
        $height = 200;

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setCaptureDimensions($width, $height);

        $this->assertSame($width, $captureRequest->getRectWidth());
    }

    /**
     * Test set capture dimensions sets
     * rect height.
     *
     * @access public
     * @return void
     */
    public function testSetCaptureDimensionsSetsRectHeight()
    {
        $width  = 100;
        $height = 200;

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setCaptureDimensions($width, $height);

        $this->assertSame($height, $captureRequest->getRectHeight());
    }

    /**
     * Test set capture dimensions sets
     * rect top.
     *
     * @access public
     * @return void
     */
    public function testSetCaptureDimensionsSetsRectTop()
    {
        $width  = 100;
        $height = 200;
        $top    = 50;

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setCaptureDimensions($width, $height, $top);

        $this->assertSame($top, $captureRequest->getRectTop());
    }

    /**
     * Test set capture dimensions sets
     * rect left.
     *
     * @access public
     * @return void
     */
    public function testSetCaptureDimensionsSetsRectLeft()
    {
        $width  = 100;
        $height = 200;
        $left   = 50;

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setCaptureDimensions($width, $height, 0, $left);

        $this->assertSame($left, $captureRequest->getRectLeft());
    }

    /**
     * Test set URL throws invalid
     * URL exception if an invalid
     * URL is set.
     *
     * @access public
     * @return void
     */
    public function testSetUrlThrowsInvalidUrlExceptionIfAnInvalidUrlIsSet()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\InvalidUrlException');

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setUrl('\\AnInvalidUrl');
    }

    /**
     * Test get URL returns URL without query
     * paramters if method is not HEAD or GET.
     *
     * @access public
     * @return void
     */
    public function testGetUrlReturnsUrlWithoutQueryParametersIfMethodIsNotHeadOrGet()
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

        $this->assertSame($url, $captureRequest->getUrl());
    }

    /**
     * Test get URL returns URL with query
     * parameters if method is GET.
     *
     * @access public
     * @return void
     */
    public function testGetUrlReturnsUrlWithQueryParametersIfMethodIsGet()
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

        $this->assertSame($expectedUrl, $captureRequest->getUrl());
    }

    /**
     * Test get URL returns URL with query
     * parameters if method is HEAD.
     *
     * @access public
     * @return void
     */
    public function testGetUrlReturnsUrlWithQueryParametersIfMethodIsHead()
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

        $this->assertSame($expectedUrl, $captureRequest->getUrl());
    }

    /**
     * Test get URL returns URL with query
     * parameters if method is HEAD.
     *
     * @access public
     * @return void
     */
    public function testGetUrlAppendsQueryParametersIfUrlHasExistingQueryParameters()
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

        $this->assertSame($expectedUrl, $captureRequest->getUrl());
    }

    /**
     * Test get body returns an empty
     * string if method is GET.
     *
     * @access public
     * @return void
     */
    public function testGetBodyReturnsAnEmptyStringIfMethodIsGet()
    {
        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => 'Testing2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('GET');
        $captureRequest->setRequestData($data);

        $this->assertSame('', $captureRequest->getBody());
    }

    /**
     * Test get body returns and empty
     * string if method is HEAD.
     *
     * @access public
     * @return void
     */
    public function testGetBodyReturnsAnEmptyStringIfMethodIsHead()
    {
        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => 'Testing2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('HEAD');
        $captureRequest->setRequestData($data);

        $this->assertSame('', $captureRequest->getBody());
    }

    /**
     * Test get body returns query string if
     * method is not HEAD or GET.
     *
     * @access public
     * @return void
     */
    public function testGetBodyReturnsQueryStringIfMethodIsNotHeadOrGet()
    {
        $data = array(
            'test_param1' => 'Testing1',
            'test_param2' => 'Testing2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setMethod('POST');
        $captureRequest->setRequestData($data);

        $body = 'test_param1=Testing1&test_param2=Testing2';

        $this->assertSame($body, $captureRequest->getBody());
    }

    /**
     * Test get request data returns flattened
     * request data if flatten is set to true.
     *
     * @access public
     * @return void
     */
    public function testGetRequestDataReturnsFlattenedRequestDataIfFlattenIsSetToTrue()
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

        $this->assertSame($flatData, $captureRequest->getRequestData(true));
    }

    /**
     * Test get request data returns unflattened
     * request data if flatten is set to false
     *
     * @access public
     * @return void
     */
    public function testGetRequestDataReturnsUnflattenedRequestDataIfFlattenIsSetToFalse()
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

        $this->assertSame($data, $captureRequest->getRequestData(false));
    }

    /**
     * Test add headers merge headers with
     * existing headers.
     *
     * @access public
     * @return void
     */
    public function testAddHeadersMergesHeadersWithExistingHeaders()
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

        $this->assertSame($expectedHeaders, $captureRequest->getHeaders());
    }

    /**
     * Test get headers returns JSON encoded
     * headers if format is set to JSON.
     *
     * @access public
     * @return void
     */
    public function testGetHeadersReturnsJsonEncodedHeadersIfFormatIsSetToJson()
    {
        $headers = array(
            'Header1' => 'Header 1',
            'Header2' => 'Header 2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setHeaders($headers);

        $expectedHeaders = json_encode($headers);

        $this->assertSame($expectedHeaders, $captureRequest->getHeaders('json'));
    }

    /**
     * Test get headers returns headers as
     * array if format is not set to json
     *
     * @access public
     * @return void
     */
    public function testGetHeadersReturnsHeadersAsArrayIfFormatIsNotSetToJson()
    {
        $headers = array(
            'Header1' => 'Header 1',
            'Header2' => 'Header 2'
        );

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setHeaders($headers);

        $this->assertSame($headers, $captureRequest->getHeaders('default'));
    }

    /**
     * Test set capture file throws not
     * writable exception if file path
     * is not writable.
     *
     * @access public
     * @return void
     */
    public function testSetCaptureFileThrowsNotWriteableExceptionIfFilePathIsNotWriteable()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotWritableException');

        $invalidPath = '/invalid/path';

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setCaptureFile($invalidPath);
    }

    /**
     * Test get capture file returns capture
     * file if capture file is set.
     *
     * @access public
     * @return void
     */
    public function testGetCaptureFileReturnsCaptureFileIfCaptureFileIsSet()
    {
        $captureFile = sprintf('%s/test.jpg', sys_get_temp_dir());

        $captureRequest = $this->getCaptureRequest();
        $captureRequest->setCaptureFile($captureFile);

        $this->assertSame($captureFile, $captureRequest->getCaptureFile());
    }

    /**
     * Test set viewport size sets viewport width.
     *
     * @access public
     * @return void
     */
    public function testSetViewportSizeSetsViewportWidth()
    {
        $width  = 100;
        $height = 200;

        $caputreRequest = $this->getCaptureRequest();
        $caputreRequest->setViewportSize($width, $height);

        $this->assertSame($width, $caputreRequest->getViewportWidth());
    }

    /**
     * Test set viewport size sets viewport height.
     *
     * @access public
     * @return void
     */
    public function testSetViewportSizeSetsViewportHeight()
    {
        $width  = 100;
        $height = 200;

        $caputreRequest = $this->getCaptureRequest();
        $caputreRequest->setViewportSize($width, $height);

        $this->assertSame($height, $caputreRequest->getViewportHeight());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get capture request instance.
     *
     * @access protected
     * @param  string                                   $url     (default: null)
     * @param  string                                   $method  (default: RequestInterface::METHOD_GET)
     * @param  int                                      $timeout (default: 5000)
     * @return \JonnyW\PhantomJs\Message\CaptureRequest
     */
    protected function getCaptureRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        $captureRequest = new CaptureRequest($url, $method, $timeout);

        return $captureRequest;
    }
}

<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Message;

use JonnyW\PhantomJs\Message\Request;
use JonnyW\PhantomJs\Message\RequestInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test get type returns default request
     * type if not type is set.
     *
     * @access public
     * @return void
     */
    public function testGetTypeReturnsDefaultRequestTypeIfNoTypeIsSet()
    {
        $request = $this->getRequest();

        $this->assertSame(RequestInterface::REQUEST_TYPE_DEFAULT, $request->getType());
    }

    /**
     * Test get type returns set request
     * type.
     *
     * @access public
     * @return void
     */
    public function testGetTypeReturnsSetRequestType()
    {
        $requestType = 'testType';

        $request = $this->getRequest();
        $request->setType($requestType);

        $this->assertSame($requestType, $request->getType());
    }

    /**
     * Test URL can be set via constructor.
     *
     * @access public
     * @return void
     */
    public function testUrlCanBeSetViaConstructor()
    {
        $url     = 'http://test.com';
        $request = $this->getRequest($url);

        $this->assertSame($url, $request->getUrl());
    }

    /**
     * Test method can be set via constructor.
     *
     * @access public
     * @return void
     */
    public function testMethodCanBeSetViaConstructor()
    {
        $method  = 'GET';
        $request = $this->getRequest(null, $method);

        $this->assertSame($method, $request->getMethod());
    }

    /**
     * Test timeout can be set via constructor.
     *
     * @access public
     * @return void
     */
    public function testTimeoutCanBeSetViaConstructor()
    {
        $timeout = 100000;
        $request = $this->getRequest('http://test.com', 'GET', $timeout);

        $this->assertSame($timeout, $request->getTimeout());
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

        $request = $this->getRequest();
        $request->setMethod('INVALID_METHOD');
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

        $this->getRequest('http://test.com', 'INVALID_METHOD');
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

        $request = $this->getRequest();
        $request->setUrl('\\AnInvalidUrl');
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

        $request = $this->getRequest();
        $request->setMethod('POST');
        $request->setUrl($url);
        $request->setRequestData($data);

        $this->assertSame($url, $request->getUrl());
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

        $request = $this->getRequest();
        $request->setMethod('GET');
        $request->setUrl($url);
        $request->setRequestData($data);

        $expectedUrl = $url . '?test_param1=Testing1&test_param2=Testing2';

        $this->assertSame($expectedUrl, $request->getUrl());
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

        $request = $this->getRequest();
        $request->setMethod('HEAD');
        $request->setUrl($url);
        $request->setRequestData($data);

        $expectedUrl = $url . '?test_param1=Testing1&test_param2=Testing2';

        $this->assertSame($expectedUrl, $request->getUrl());
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

        $request = $this->getRequest();
        $request->setMethod('GET');
        $request->setUrl($url);
        $request->setRequestData($data);

        $expectedUrl = $url . '&test_param1=Testing1&test_param2=Testing2';

        $this->assertSame($expectedUrl, $request->getUrl());
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

        $request = $this->getRequest();
        $request->setMethod('GET');
        $request->setRequestData($data);

        $this->assertSame('', $request->getBody());
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

        $request = $this->getRequest();
        $request->setMethod('HEAD');
        $request->setRequestData($data);

        $this->assertSame('', $request->getBody());
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

        $request = $this->getRequest();
        $request->setMethod('POST');
        $request->setRequestData($data);

        $body = 'test_param1=Testing1&test_param2=Testing2';

        $this->assertSame($body, $request->getBody());
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

        $request = $this->getRequest();
        $request->setRequestData($data);

        $flatData = array(
            'test_param1'    => 'Testing1',
            'test_param2[0]' => 'Testing2',
            'test_param2[1]' => 'Testing3'
        );

        $this->assertSame($flatData, $request->getRequestData(true));
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

        $request = $this->getRequest();
        $request->setRequestData($data);

        $this->assertSame($data, $request->getRequestData(false));
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

        $request = $this->getRequest();
        $request->setHeaders($existingHeaders);
        $request->addHeaders($newHeaders);

        $expectedHeaders = array_merge($existingHeaders, $newHeaders);

        $this->assertSame($expectedHeaders, $request->getHeaders());
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

        $request = $this->getRequest();
        $request->setHeaders($headers);

        $expectedHeaders = json_encode($headers);

        $this->assertSame($expectedHeaders, $request->getHeaders('json'));
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

        $request = $this->getRequest();
        $request->setHeaders($headers);

        $this->assertSame($headers, $request->getHeaders('default'));
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

        $request = $this->getRequest();
        $request->setViewportSize($width, $height);

        $this->assertSame($width, $request->getViewportWidth());
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

        $request = $this->getRequest();
        $request->setViewportSize($width, $height);

        $this->assertSame($height, $request->getViewportHeight());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get request instance.
     *
     * @access protected
     * @param  string                            $url     (default: null)
     * @param  string                            $method  (default: RequestInterface::METHOD_GET)
     * @param  int                               $timeout (default: 5000)
     * @return \JonnyW\PhantomJs\Message\Request
     */
    protected function getRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        $request = new Request($url, $method, $timeout);

        return $request;
    }
}

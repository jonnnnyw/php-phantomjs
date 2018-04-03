<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Http;

use JonnyW\PhantomJs\Http\PdfRequest;
use JonnyW\PhantomJs\Http\RequestInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class PdfRequestTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test PDF type is returned by default
     * if no type is set.
     *
     * @access public
     * @return void
     */
    public function testPdfTypeIsReturnedByDefaultIfNotTypeIsSet()
    {
        $pdfRequest = $this->getPdfRequest();

        $this->assertEquals(RequestInterface::REQUEST_TYPE_PDF, $pdfRequest->getType());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setType($requestType);

        $this->assertEquals($requestType, $pdfRequest->getType());
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
        $pdfRequest = $this->getPdfRequest($url);

        $this->assertEquals($url, $pdfRequest->getUrl());
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
        $pdfRequest = $this->getPdfRequest(null, $method);

        $this->assertEquals($method, $pdfRequest->getMethod());
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
        $pdfRequest = $this->getPdfRequest('http://test.com', 'GET', $timeout);

        $this->assertEquals($timeout, $pdfRequest->getTimeout());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setMethod('INVALID_METHOD');
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setMethod('POST');
        $pdfRequest->setUrl($url);
        $pdfRequest->setRequestData($data);

        $this->assertEquals($url, $pdfRequest->getUrl());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setMethod('GET');
        $pdfRequest->setUrl($url);
        $pdfRequest->setRequestData($data);

        $expectedUrl = $url . '?test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($expectedUrl, $pdfRequest->getUrl());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setMethod('HEAD');
        $pdfRequest->setUrl($url);
        $pdfRequest->setRequestData($data);

        $expectedUrl = $url . '?test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($expectedUrl, $pdfRequest->getUrl());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setMethod('GET');
        $pdfRequest->setUrl($url);
        $pdfRequest->setRequestData($data);

        $expectedUrl = $url . '&test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($expectedUrl, $pdfRequest->getUrl());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setMethod('GET');
        $pdfRequest->setRequestData($data);

        $this->assertEquals('', $pdfRequest->getBody());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setMethod('HEAD');
        $pdfRequest->setRequestData($data);

        $this->assertEquals('', $pdfRequest->getBody());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setMethod('POST');
        $pdfRequest->setRequestData($data);

        $body = 'test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($body, $pdfRequest->getBody());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setRequestData($data);

        $flatData = array(
            'test_param1'    => 'Testing1',
            'test_param2[0]' => 'Testing2',
            'test_param2[1]' => 'Testing3'
        );

        $this->assertEquals($flatData, $pdfRequest->getRequestData(true));
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setRequestData($data);

        $this->assertEquals($data, $pdfRequest->getRequestData(false));
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setHeaders($existingHeaders);
        $pdfRequest->addHeaders($newHeaders);

        $expectedHeaders = array_merge($existingHeaders, $newHeaders);

        $this->assertEquals($expectedHeaders, $pdfRequest->getHeaders());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setHeaders($headers);

        $expectedHeaders = json_encode($headers);

        $this->assertEquals($expectedHeaders, $pdfRequest->getHeaders('json'));
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setHeaders($headers);

        $this->assertEquals($headers, $pdfRequest->getHeaders('default'));
    }

    /**
     * Test not writable exception is thrown if
     * output path is not writable.
     *
     * @access public
     * @return void
     */
    public function tesNotWritableExceptonIsThrownIfOutputPathIsNotWritable()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotWritableException');

        $invalidPath = '/invalid/path';

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setOutputFile($invalidPath);
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setOutputFile($outputFile);

        $this->assertEquals($outputFile, $pdfRequest->getOutputFile());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setViewportSize($width, $height);

        $this->assertEquals($width, $pdfRequest->getViewportWidth());
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

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setViewportSize($width, $height);

        $this->assertEquals($height, $pdfRequest->getViewportHeight());
    }

    /**
     * Test can set paper width.
     *
     * @access public
     * @return void
     */
    public function testCanSetPaperWidth()
    {
        $width  = '10cm';
        $height = '20cm';

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setPaperSize($width, $height);

        $this->assertEquals($width, $pdfRequest->getPaperWidth());
    }

    /**
     * Test can set paper height.
     *
     * @access public
     * @return void
     */
    public function testCanSetPaperHeight()
    {
        $width  = '10cm';
        $height = '20cm';

        $pdfRequest = $this->getPdfRequest();
        $pdfRequest->setPaperSize($width, $height);

        $this->assertEquals($height, $pdfRequest->getPaperHeight());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get PDF request instance.
     *
     * @access protected
     * @param  string                            $url     (default: null)
     * @param  string                            $method  (default: RequestInterface::METHOD_GET)
     * @param  int                               $timeout (default: 5000)
     * @return \JonnyW\PhantomJs\Http\PdfRequest
     */
    protected function getPdfRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        $pdfRequest = new PdfRequest($url, $method, $timeout);

        return $pdfRequest;
    }
}

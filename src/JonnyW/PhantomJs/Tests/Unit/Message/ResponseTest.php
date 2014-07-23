<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Message;

use JonnyW\PhantomJs\Message\Response;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test import sets status in response.
     *
     * @access public
     * @return void
     */
    public function testImportSetsStatusInResponse()
    {
        $data = array(
            'status' => 200
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertSame(200, $response->getStatus());
    }

    /**
     * Test import sets content in response.
     *
     * @access public
     * @return void
     */
    public function testImportSetsContentInResponse()
    {
        $data = array(
            'content' => 'Test content'
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertSame('Test content', $response->getContent());
    }

    /**
     * Test import sets content in response.
     *
     * @access public
     * @return void
     */
    public function testImportSetsContentTypeInResponse()
    {
        $data = array(
            'contentType' => 'text/html'
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertSame('text/html', $response->getContentType());
    }

    /**
     * Test import sets URL in response.
     *
     * @access public
     * @return void
     */
    public function testImportSetsUrlInResponse()
    {
        $data = array(
            'url' => 'http://test.com'
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertSame('http://test.com', $response->getUrl());
    }

    /**
     * Test import sets redirect URL in response.
     *
     * @access public
     * @return void
     */
    public function testImportSetsRedirectUrlInResponse()
    {
        $data = array(
            'redirectUrl' => 'http://test.com'
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertSame('http://test.com', $response->getRedirectUrl());
    }

    /**
     * Test import sets time in response.
     *
     * @access public
     * @return void
     */
    public function testImportSetsTimeInResponse()
    {
        $data = array(
            'time' => 123456789
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertSame(123456789, $response->getTime());
    }

    /**
     * Test import sets headers in response.
     *
     * @access public
     * @return void
     */
    public function testImportSetsHeadersInResponse()
    {
        $headers = array(
            array(
                'name'  => 'Header1',
                'value' => 'Test Header 1'
            )
        );

        $data = array(
            'headers' => $headers
        );

        $response = $this->getResponse();
        $response->import($data);

        $expectedHeaders = array(
            $headers[0]['name'] => $headers[0]['value']
        );

        $this->assertSame($expectedHeaders, $response->getHeaders());
    }

    /**
     * Test get header returns null if header
     * is not set.
     *
     * @access public
     * @return void
     */
    public function testGetHeadersReturnsNullIfHeaderIsNotSet()
    {
        $response = $this->getResponse();

        $this->assertNull($response->getHeader('invalid_header'));
    }

    /**
     * Test get header returns header if
     * header is set.
     *
     * @access public
     * @return void
     */
    public function testGetHeaderReturnsHeaderIfHeaderIsSet()
    {
        $headers = array(
            array(
                'name'  => 'Header1',
                'value' => 'Test Header 1'
            )
        );

        $data = array(
            'headers' => $headers
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertSame('Test Header 1', $response->getHeader('Header1'));
    }

    /**
     * Test is redirect returns true if
     * status equals 300.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectReturnsTrueIfStatusEquals300()
    {
        $data = array(
            'status' => 300
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect returns true if
     * status equals 301.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectReturnsTrueIfStatusEquals301()
    {
        $data = array(
            'status' => 301
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect returns true if
     * status equals 302.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectReturnsTrueIfStatusEquals302()
    {
        $data = array(
            'status' => 302
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect returns true if
     * status equals 303.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectReturnsTrueIfStatusEquals303()
    {
        $data = array(
            'status' => 303
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect returns true if
     * status equals 304.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectReturnsTrueIfStatusEquals304()
    {
        $data = array(
            'status' => 304
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect returns true if
     * status equals 305.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectReturnsTrueIfStatusEquals305()
    {
        $data = array(
            'status' => 305
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect returns true if
     * status equals 306.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectReturnsTrueIfStatusEquals306()
    {
        $data = array(
            'status' => 306
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect returns true if
     * status equals 307.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectReturnsTrueIfStatusEquals307()
    {
        $data = array(
            'status' => 307
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect returns false if status
     * code is not a valid redirect code.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectReturnsFalseIfStatusCodeIsNotAValidRedirectCode()
    {
        $data = array(
            'status' => 401
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertFalse($response->isRedirect());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get response instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Message\Response
     */
    protected function getResponse()
    {
        $response = new Response();

        return $response;
    }
}

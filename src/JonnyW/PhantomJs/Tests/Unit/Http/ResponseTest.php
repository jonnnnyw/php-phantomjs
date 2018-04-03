<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Http;

use JonnyW\PhantomJs\Http\Response;

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
     * Test status can be imported.
     *
     * @access public
     * @return void
     */
    public function testStatusCanBeImported()
    {
        $data = array(
            'status' => 200
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertEquals(200, $response->getStatus());
    }

    /**
     * Test content can be imported
     *
     * @access public
     * @return void
     */
    public function testContentCanBeImported()
    {
        $data = array(
            'content' => 'Test content'
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertEquals('Test content', $response->getContent());
    }

    /**
     * Test content type can be imported.
     *
     * @access public
     * @return void
     */
    public function testContentTypeCanBeImported()
    {
        $data = array(
            'contentType' => 'text/html'
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertEquals('text/html', $response->getContentType());
    }

    /**
     * Test URL can be imported.
     *
     * @access public
     * @return void
     */
    public function testUrlCanBeImported()
    {
        $data = array(
            'url' => 'http://test.com'
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertEquals('http://test.com', $response->getUrl());
    }

    /**
     * Test redirect URL can be imported.
     *
     * @access public
     * @return void
     */
    public function testRedirectUrlCanBeImported()
    {
        $data = array(
            'redirectURL' => 'http://test.com'
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertEquals('http://test.com', $response->getRedirectUrl());
    }

    /**
     * Test time can be imported.
     *
     * @access public
     * @return void
     */
    public function testTimeCanBeImported()
    {
        $data = array(
            'time' => 123456789
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertEquals(123456789, $response->getTime());
    }

    /**
     * Test headers can be imported.
     *
     * @access public
     * @return void
     */
    public function testHeadersCanBeImported()
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

        $this->assertEquals($expectedHeaders, $response->getHeaders());
    }

    /**
     * Test null is returned if header is not set.
     *
     * @access public
     * @return void
     */
    public function testNullIsReturnedIfHeaderIsNotSet()
    {
        $response = $this->getResponse();

        $this->assertNull($response->getHeader('invalid_header'));
    }

    /**
     * Test can get header.
     *
     * @access public
     * @return void
     */
    public function testCanGetHeader()
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

        $this->assertEquals('Test Header 1', $response->getHeader('Header1'));
    }

    /**
     * Test is redirect if status code
     * is 300.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectIfStatusCodeIs300()
    {
        $data = array(
            'status' => 300
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect if status code
     * is 301.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectIfStatusCodeIs301()
    {
        $data = array(
            'status' => 301
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect if status code
     * is 302.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectIfStatusCodeIs302()
    {
        $data = array(
            'status' => 302
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect if status code
     * is 303.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectIfStatusCodeIs303()
    {
        $data = array(
            'status' => 303
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect if status code
     * is 304.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectIfStatusCodeIs304()
    {
        $data = array(
            'status' => 304
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect if status code
     * is 305.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectIfStatusCodeIs305()
    {
        $data = array(
            'status' => 305
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect if status code
     * is 306.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectIfStatusCodeIs306()
    {
        $data = array(
            'status' => 306
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is redirect if status code
     * is 307.
     *
     * @access public
     * @return void
     */
    public function testIsRedirectIfStatusCodeIs307()
    {
        $data = array(
            'status' => 307
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Test is not redirect if status code is
     * not redirect.
     *
     * @access public
     * @return void
     */
    public function testIsNotRedirectIfStatusCodeIsNotRedirect()
    {
        $data = array(
            'status' => 401
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertFalse($response->isRedirect());
    }

    /**
     * Test if cookies can be parsed and imported
     *
     * @access public
     * @return void
     */
    public function testCookiesCanBeImported()
    {
        $cookie = 'cookie=TESTING; HttpOnly; expires=Mon, 16-Nov-2020 00:00:00 GMT; domain=.jonnyw.kiwi; path=/';
        $data = array(
            'cookies' => array($cookie)
        );

        $response = $this->getResponse();
        $response->import($data);

        $this->assertContains($cookie, $response->getCookies());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get response instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Http\Response
     */
    protected function getResponse()
    {
        $response = new Response();

        return $response;
    }
}

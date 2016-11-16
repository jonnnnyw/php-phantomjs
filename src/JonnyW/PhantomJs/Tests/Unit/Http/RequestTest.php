<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Http;

use JonnyW\PhantomJs\Http\Request;
use JonnyW\PhantomJs\Http\RequestInterface;

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
     * Test default type is returned by default
     * if no type is set.
     *
     * @access public
     * @return void
     */
    public function testDefaultTypeIsReturnedByDefaultIfNotTypeIsSet()
    {
        $request = $this->getRequest();

        $this->assertEquals(RequestInterface::REQUEST_TYPE_DEFAULT, $request->getType());
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

        $request = $this->getRequest();
        $request->setType($requestType);

        $this->assertEquals($requestType, $request->getType());
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
        $request = $this->getRequest($url);

        $this->assertEquals($url, $request->getUrl());
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
        $request = $this->getRequest(null, $method);

        $this->assertEquals($method, $request->getMethod());
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
        $request = $this->getRequest('http://test.com', 'GET', $timeout);

        $this->assertEquals($timeout, $request->getTimeout());
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

        $request = $this->getRequest();
        $request->setMethod('INVALID_METHOD');
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

        $request = $this->getRequest();
        $request->setMethod('POST');
        $request->setUrl($url);
        $request->setRequestData($data);

        $this->assertEquals($url, $request->getUrl());
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

        $request = $this->getRequest();
        $request->setMethod('GET');
        $request->setUrl($url);
        $request->setRequestData($data);

        $expectedUrl = $url . '?test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($expectedUrl, $request->getUrl());
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

        $request = $this->getRequest();
        $request->setMethod('HEAD');
        $request->setUrl($url);
        $request->setRequestData($data);

        $expectedUrl = $url . '?test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($expectedUrl, $request->getUrl());
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

        $request = $this->getRequest();
        $request->setMethod('GET');
        $request->setUrl($url);
        $request->setRequestData($data);

        $expectedUrl = $url . '&test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($expectedUrl, $request->getUrl());
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

        $request = $this->getRequest();
        $request->setMethod('GET');
        $request->setRequestData($data);

        $this->assertEquals('', $request->getBody());
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

        $request = $this->getRequest();
        $request->setMethod('HEAD');
        $request->setRequestData($data);

        $this->assertEquals('', $request->getBody());
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

        $request = $this->getRequest();
        $request->setMethod('POST');
        $request->setRequestData($data);

        $body = 'test_param1=Testing1&test_param2=Testing2';

        $this->assertEquals($body, $request->getBody());
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

        $request = $this->getRequest();
        $request->setRequestData($data);

        $flatData = array(
            'test_param1'    => 'Testing1',
            'test_param2[0]' => 'Testing2',
            'test_param2[1]' => 'Testing3'
        );

        $this->assertEquals($flatData, $request->getRequestData(true));
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

        $request = $this->getRequest();
        $request->setRequestData($data);

        $this->assertEquals($data, $request->getRequestData(false));
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

        $request = $this->getRequest();
        $request->setHeaders($existingHeaders);
        $request->addHeaders($newHeaders);

        $expectedHeaders = array_merge($existingHeaders, $newHeaders);

        $this->assertEquals($expectedHeaders, $request->getHeaders());
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

        $request = $this->getRequest();
        $request->setHeaders($headers);

        $expectedHeaders = json_encode($headers);

        $this->assertEquals($expectedHeaders, $request->getHeaders('json'));
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

        $request = $this->getRequest();
        $request->setHeaders($headers);

        $this->assertEquals($headers, $request->getHeaders('default'));
    }

    /**
     * Test can add setting.
     *
     * @access public
     * @return void
     */
    public function testCanAddSetting()
    {
        $request = $this->getRequest();
        $request->addSetting('userAgent', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36');
        $request->addSetting('localToRemoteUrlAccessEnabled', 'true');
        $request->addSetting('resourceTimeout', 3000);

        $expected = array(
            'userAgent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36',
            'localToRemoteUrlAccessEnabled' => 'true',
            'resourceTimeout' => 3000
        );

        $this->assertEquals($expected, $request->getSettings());
    }

    /**
     * Test set timeout sets resource
     * timeout in settings
     *
     * @access public
     * @return void
     */
    public function testSetTimeoutSetsResourceTimeoutInSettings()
    {
        $request = $this->getRequest();
        $request->setTimeout(1000);

        $expected = array(
            'resourceTimeout' => 1000
        );

        $this->assertEquals($expected, $request->getSettings());
    }

    /**
     * Test can add cookies.
     *
     * @access public
     * @return void
     */
    public function testCanAddCookies()
    {
        $name     = 'test_cookie';
        $value    = 'TESTING_COOKIES';
        $path     = '/';
        $domain   = 'localhost';
        $httpOnly =  false;
        $secure   = true;
        $expires  = time() + 3600;

        $request = $this->getRequest();
        $request->addCookie(
            $name,
            $value,
            $path,
            $domain,
            $httpOnly,
            $secure,
            $expires
        );

        $expected = array(
            'name'     => $name,
            'value'    => $value,
            'path'     => $path,
            'domain'   => $domain,
            'httponly' => $httpOnly,
            'secure'   => $secure,
            'expires'  => $expires
        );
        
        $cookies = $request->getCookies();
        
        $this->assertEquals(array($expected), $cookies['add']);
    }

    /**
     * Test can delete cookies.
     *
     * @access public
     * @return void
     */
    public function testCanDeleteCookies()
    {
        $name     = 'test_cookie';
        $value    = 'TESTING_COOKIES';
        $path     = '/';
        $domain   = 'localhost';
        $httpOnly =  false;
        $secure   = true;
        $expires  = time() + 3600;

        $request = $this->getRequest();
        $request->addCookie(
            $name,
            $value,
            $path,
            $domain,
            $httpOnly,
            $secure,
            $expires
        );

        $request->deleteCookie($name);

        $cookies = $request->getCookies();

        $this->assertEquals(array($name), $cookies['delete']);
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

        $request = $this->getRequest();
        $request->setViewportSize($width, $height);

        $this->assertEquals($width, $request->getViewportWidth());
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

        $request = $this->getRequest();
        $request->setViewportSize($width, $height);

        $this->assertEquals($height, $request->getViewportHeight());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get request instance.
     *
     * @access protected
     * @param  string                         $url     (default: null)
     * @param  string                         $method  (default: RequestInterface::METHOD_GET)
     * @param  int                            $timeout (default: 5000)
     * @return \JonnyW\PhantomJs\Http\Request
     */
    protected function getRequest($url = null, $method = RequestInterface::METHOD_GET, $timeout = 5000)
    {
        $request = new Request($url, $method, $timeout);

        return $request;
    }
}

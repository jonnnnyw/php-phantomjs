<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Test\Message;

use JonnyW\PhantomJs\Message\Request;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test invalid URL's throw exception
     *
     * @dataProvider provideInvalidHosts
     *
     * @param  string $url
     * @return void
     */
    public function testInvalidUrl($url)
    {
        $this->setExpectedException('JonnyW\\PhantomJs\\Exception\\InvalidUrlException');

        $request = new Request();
        $request->setUrl($url);
    }

    /**
     * Invalid host data providers
     *
     * @return array
     */
    public function provideInvalidHosts()
    {
        return array(
            array('invalid_url'),
            array('invalid_url.test')
        );
    }

    /**
     * Test invalid methods throw exception
     *
     * @dataProvider provideInvalidMethods
     *
     * @param  string $method
     * @return void
     */
    public function testInvalidMethod($method)
    {
        $this->setExpectedException('JonnyW\\PhantomJs\\Exception\\InvalidMethodException');

        $request = new Request();
        $request->setMethod($method);
    }

    /**
     * Invalid method data providers
     *
     * @return array
     */
    public function provideInvalidMethods()
    {
        return array(
            array('GOT'),
            array('FIND')
        );
    }

    /**
     * Test invalid methods throw exception
     *
     * @return void
     */
    public function testGetRequestBody()
    {
        $data = array('name' => 'jonnyw', 'email' => 'contact@jonnyw.me');

        $request = new Request('GET', 'http://jonnyw.me');
        $request->setRequestData($data);

        $this->assertEmpty($request->getBody());
    }

    /**
     * Test GET URL parameters when URL
     * does not have existing parameters
     *
     * @return void
     */
    public function testGetUrlQuery()
    {
        $data = array('name' => 'jonnyw', 'email' => 'contact@jonnyw.me');

        $request = new Request('GET', 'http://jonnyw.me?query=true');
        $request->setRequestData($data);

        $this->assertEquals($request->getUrl(), 'http://jonnyw.me?query=true&' . urldecode(http_build_query($data)));
    }

    /**
     * Test GET URL parameters when URL
     * does not have existing parameters
     *
     * @return void
     */
    public function testGetUrlQueryClean()
    {
        $data = array('name' => 'jonnyw', 'email' => 'contact@jonnyw.me');

        $request = new Request('GET', 'http://jonnyw.me');
        $request->setRequestData($data);

        $this->assertEquals($request->getUrl(), 'http://jonnyw.me?' . urldecode(http_build_query($data)));
    }

    /**
     * Test invalid methods throw exception
     *
     * @return void
     */
    public function testPostRequestBody()
    {
        $data = array('name' => 'jonnyw', 'email' => 'contact@jonnyw.me');

        $request = new Request('POST', 'http://jonnyw.me');
        $request->setRequestData($data);

        $this->assertEquals($request->getBody(), urldecode(http_build_query($data)));
    }
}

<?php

/*
 * This file is part of the php-phantomjs.
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Test;

use JonnyW\PhantomJs\Client;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ClientTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Setup tests
	 *
	 * @return void
	 */
	protected function setUp()
    {
        parent::setUp();
        
        $this->client = Client::getInstance();
    }

	/**
	 * Test invalid URL's throw exception
	 *
     * @dataProvider provideInvalidHosts
     *
     * @param string $host
	 * @return void
     */
    public function testInvalidUrl($host)
    {
        $this->setExpectedException('JonnyW\\PhantomJs\\Exception\\InvalidUrlException');

        $this->client->open($host);
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
     * Test exception is thrown when 
     * PhantomJS executable cannot be run
     *
     * @return void
     */
    public function testBinNotExecutable()
    {
	    $this->setExpectedException('JonnyW\\PhantomJs\\Exception\\NoPhantomJsException');

        $this->client->setPhantomJs('/path/does/not/exist/phantomjs');
    }

	/**
     * Test exception is thrown when capture
     * path is not writeable
     *
     * @return void
     */
    public function testPathNotWriteable()
    {
	    $this->setExpectedException('JonnyW\\PhantomJs\\Exception\\NotWriteableException');

        $this->client->capture('http://google.com', 'path/does/not/exist/phantoms.png');
    }

	/**
	 * Test open page
	 *
	 * @return void
	 */
	public function testOpenPage()
	{
		$client 	= $this->getMock('JonnyW\PhantomJs\Client', array('request'));
		$response 	= $this->getMock('JonnyW\PhantomJs\Response', null, array(array('content' => 'test')));
		
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnValue($response));
		
		$actual = $client->open('http://jonnyw.me');
		
		$this->assertSame($response, $actual);
	}
	
	/**
	 * Test screen capture page
	 *
	 * @return void
	 */
	public function testCapturePage()
	{
		$client 	= $this->getMock('JonnyW\PhantomJs\Client', array('request'));
		$response 	= $this->getMock('JonnyW\PhantomJs\Response', null, array(array('content' => 'test')));
		
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnValue($response));
		
		$actual = $client->capture('http://jonnyw.me', '/tmp/testing.png');
		
		$this->assertSame($response, $actual);
	}
	
	/**
	 * Test page is redirect
	 *
	 * @return void
	 */
	public function testRedirectPage()
	{
		$client 	= $this->getMock('JonnyW\PhantomJs\Client', array('request'));
		$response 	= $this->getMock('JonnyW\PhantomJs\Response', null, array(array('content' => 'test', 'status' => 301, 'redirectUrl' => 'http://google.com')));
		
        $client->expects($this->once())
            ->method('request')
            ->will($this->returnValue($response));
		
		$actual = $client->open('http://jonnyw.me');
		
		$this->assertTrue($response->isRedirect());
	}
}
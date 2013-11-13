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
	 * Client instance
	 *
	 * @var JonnyW\PhantomJs\Client
	 */
	protected $client;

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
	 * Test get factory instance
	 *
	 * @return void
	 */
	public function testMessageFactoryInstance()
	{
		$factory = $this->client->getMessageFactory();

		$this->assertInstanceOf('JonnyW\PhantomJs\Message\FactoryInterface', $factory);
	}

	/**
	 * Test exception is thrown when
	 * PhantomJS executable cannot be run
	 *
	 * @return void
	 */
	public function testBinNotExecutable()
	{
		$this->setExpectedException('JonnyW\PhantomJs\Exception\NoPhantomJsException');

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
		$this->setExpectedException('JonnyW\PhantomJs\Exception\NotWriteableException');

		$request  = $this->getMock('JonnyW\PhantomJs\Message\Request', null, array('method' => 'GET', 'url' => 'http://jonnyw.me'));
		$response  = $this->getMock('JonnyW\PhantomJs\Message\Response', null);

		$this->client->send($request, $response, 'path/does/not/exist/phantoms.png');
	}

	/**
	 * Test open page
	 *
	 * @return void
	 */
	public function testOpenPage()
	{
		$client  = $this->getMock('JonnyW\PhantomJs\Client', array('request'));
		$request  = $this->getMock('JonnyW\PhantomJs\Message\Request', null, array('method' => 'GET', 'url' => 'http://jonnyw.me'));
		$response  = $this->getMock('JonnyW\PhantomJs\Message\Response', null);

		$client->expects($this->once())
		->method('request')
		->will($this->returnValue($response));

		$actual = $client->send($request, $response);

		$this->assertSame($response, $actual);
	}

	/**
	 * Test screen capture page
	 *
	 * @return void
	 */
	public function testCapturePage()
	{
		$client  = $this->getMock('JonnyW\PhantomJs\Client', array('request'));
		$request  = $this->getMock('JonnyW\PhantomJs\Message\Request', null, array('method' => 'GET', 'url' => 'http://jonnyw.me'));
		$response  = $this->getMock('JonnyW\PhantomJs\Message\Response', null);

		$client->expects($this->once())
		->method('request')
		->will($this->returnValue($response));

		$actual = $client->send($request, $response, '/tmp/testing.png');

		$this->assertSame($response, $actual);
	}

	/**
	 * Test page is redirect
	 *
	 * @return void
	 */
	public function testRedirectPage()
	{
		$client  = $this->getMock('JonnyW\PhantomJs\Client', array('request'));
		$request  = $this->getMock('JonnyW\PhantomJs\Message\Request', null, array('method' => 'GET', 'url' => 'http://jonnyw.me'));
		$response  = $this->getMock('JonnyW\PhantomJs\Message\Response', array('getStatus'));

		$client->expects($this->once())
		->method('request')
		->will($this->returnValue($response));

		$response->expects($this->once())
		->method('getStatus')
		->will($this->returnValue(301));

		$actual = $client->send($request, $response);

		$this->assertTrue($actual->isRedirect());
	}
}
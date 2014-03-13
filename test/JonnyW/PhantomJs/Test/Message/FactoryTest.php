<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Test\Message;

use JonnyW\PhantomJs\Message\Factory;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Factory instance
     *
     * @var \JonnyW\PhantomJs\Message\Factory
     */
    protected $factory;

    /**
     * Setup tests
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->factory = Factory::getInstance();
    }

    /**
     * Test create request instance
     *
     * @return void
     */
    public function testRequestInstance()
    {
        $request = $this->factory->createRequest();

        $this->assertInstanceOf('JonnyW\PhantomJs\Message\RequestInterface', $request);
    }

    /**
     * Test create response instance
     *
     * @return void
     */
    public function testResponseInstance()
    {
        $response = $this->factory->createResponse();

        $this->assertInstanceOf('JonnyW\PhantomJs\Message\ResponseInterface', $response);
    }
}

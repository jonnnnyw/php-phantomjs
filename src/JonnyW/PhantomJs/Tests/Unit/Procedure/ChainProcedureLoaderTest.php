<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Procedure;

use JonnyW\PhantomJs\Procedure\ChainProcedureLoader;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ChainProcedureLoaderTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test load throws invalid argument exception
     * if no valid procedure loader could be found.
     *
     * @access public
     * @return void
     */
    public function testLoadThrowsInvalidArgumentExceptionIfNoValidProcedureLoaderCouldBeFound()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $procedureLoaders = array();

        $chainProcedureLoader = $this->getChainProcedureLoader($procedureLoaders);
        $chainProcedureLoader->load('test');
    }

    /**
     * Test load throws invalid argument exception if
     * procedure loader throws exception.
     *
     * @access public
     * @return void
     */
    public function testLoadThrowsInvalidArgumentExceptionIfProcedureLoaderThrowsException()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $procedureLoader = $this->getProcedureLoader();
        $procedureLoader->method('load')
            ->will($this->throwException(new \Exception()));

        $procedureLoaders = array(
            $procedureLoader
        );

        $chainProcedureLoader = $this->getChainProcedureLoader($procedureLoaders);
        $chainProcedureLoader->load('test');
    }

    /**
     * Test load returns instance of procedure.
     *
     * @access public
     * @return void
     */
    public function testLoadReturnsInstanceOfProcedure()
    {
        $procedure = $this->getProcedure();

        $procedureLoader = $this->getProcedureLoader();
        $procedureLoader->method('load')
            ->will($this->returnValue($procedure));

        $procedureLoaders = array(
            $procedureLoader
        );

        $chainProcedureLoader = $this->getChainProcedureLoader($procedureLoaders);

        $this->assertInstanceOf('\JonnyW\PhantomJs\Procedure\ProcedureInterface', $chainProcedureLoader->load('test'));
    }

    /**
     * Test add loader adds procedure loader
     * to chain loader.
     *
     * @access public
     * @return void
     */
    public function testAddLoaderAddsProcedureLoaderToChainLoader()
    {
        $chainProcedureLoader = $this->getChainProcedureLoader(array());

        $procedureLoader =  $this->getProcedureLoader();
        $procedureLoader->expects($this->once())
            ->method('load');

        $chainProcedureLoader->addLoader($procedureLoader);
        $chainProcedureLoader->load('test');
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get chain procedure loader instance.
     *
     * @access protected
     * @param  array                                            $procedureLoaders
     * @return \JonnyW\PhantomJs\Procedure\ChainProcedureLoader
     */
    protected function getChainProcedureLoader(array $procedureLoaders)
    {
        $chainProcedureLoader = new ChainProcedureLoader($procedureLoaders);

        return $chainProcedureLoader;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ MOCKS / STUBS ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get mock procedure loader instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    protected function getProcedureLoader()
    {
        $mockProcedureLoader = $this->getMock('\JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface');

        return $mockProcedureLoader;
    }

    /**
     * Get mock procedure instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Procedure\ProcedureInterface
     */
    protected function getProcedure()
    {
        $mockProcedure = $this->getMock('\JonnyW\PhantomJs\Procedure\ProcedureInterface');

        return $mockProcedure;
    }
}

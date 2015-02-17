<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Procedure;

use Symfony\Component\Config\FileLocatorInterface;
use JonnyW\PhantomJs\Procedure\ProcedureFactoryInterface;
use JonnyW\PhantomJs\Procedure\ProcedureLoader;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test filename
     *
     * @var string
     * @access protected
     */
    protected $filename;

    /**
     * Test directory
     *
     * @var string
     * @access protected
     */
    protected $directory;

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test load throws invalid argument exception
     * if file is not a local stream.
     *
     * @access public
     * @return void
     */
    public function testLoadThrowsInvalidArgumentExceptionIfFileIsNotALocalStream()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $procedureFactory = $this->getProcedureFactory();
        $fileLocator      = $this->getFileLocator();

        $fileLocator->method('locate')
            ->will($this->returnValue('http://example.com/index.html'));

        $procedureLoader = $this->getProcedureLoader($procedureFactory, $fileLocator);
        $procedureLoader->load('test');
    }

    /**
     * Test load throws not exists exception if
     * file does not exist.
     *
     * @access public
     * @return void
     */
    public function testLoadThrowsNotExistsExceptionIfFileDoesNotExist()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotExistsException');

        $procedureFactory = $this->getProcedureFactory();
        $fileLocator      = $this->getFileLocator();

        $fileLocator->method('locate')
            ->will($this->returnValue('/invalid/file.proc'));

        $procedureLoader = $this->getProcedureLoader($procedureFactory, $fileLocator);
        $procedureLoader->load('test');
    }

    /**
     * Test load returns procedure instance.
     *
     * @access public
     * @return void
     */
    public function testLoadReturnsProcedureInstance()
    {
        $body = 'PROCEDURE BODY';
        $file = $this->writeProcedure($body);

        $procedureFactory = $this->getProcedureFactory();
        $fileLocator      = $this->getFileLocator();
        $procedure        = $this->getProcedure();

        $fileLocator->method('locate')
            ->will($this->returnValue($file));

        $procedureFactory->method('createProcedure')
            ->will($this->returnValue($procedure));

        $procedureLoader = $this->getProcedureLoader($procedureFactory, $fileLocator);

        $this->assertInstanceOf('\JonnyW\PhantomJs\Procedure\ProcedureInterface', $procedureLoader->load('test'));
    }

    /**
     * Test load sets procedure body in
     * procedure instance.
     *
     * @access public
     * @return void
     */
    public function testLoadSetsProcedureBodyInProcedureInstance()
    {
        $body = 'PROCEDURE BODY';
        $file = $this->writeProcedure($body);

        $procedureFactory = $this->getProcedureFactory();
        $fileLocator      = $this->getFileLocator();
        $procedure        = $this->getProcedure();

        $fileLocator->method('locate')
            ->will($this->returnValue($file));

        $procedureFactory->method('createProcedure')
            ->will($this->returnValue($procedure));

        $procedureLoader = $this->getProcedureLoader($procedureFactory, $fileLocator);

        $this->assertSame($body, $procedureLoader->load('test')->getProcedure());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure loader instance.
     *
     * @access public
     * @param  \JonnyW\PhantomJs\Procedure\ProcedureFactoryInterface $procedureFactory
     * @param  \Symfony\Component\Config\FileLocatorInterface        $locator
     * @return \JonnyW\PhantomJs\Procedure\ProcedureLoader
     */
    protected function getProcedureLoader(ProcedureFactoryInterface $procedureFactory, FileLocatorInterface $locator)
    {
        $procedureLoader = new ProcedureLoader($procedureFactory, $locator);

        return $procedureLoader;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ MOCKS / STUBS ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get mock procedure factory instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Procedure\ProcedureFactoryInterface
     */
    protected function getProcedureFactory()
    {
        $mockProcedureFactory = $this->getMock('\JonnyW\PhantomJs\Procedure\ProcedureFactoryInterface');

        return $mockProcedureFactory;
    }

    /**
     * Get mock file locator instance.
     *
     * @access protected
     * @return \Symfony\Component\Config\FileLocatorInterface
     */
    protected function getFileLocator()
    {
        $mockFileLocator = $this->getMock('\Symfony\Component\Config\FileLocatorInterface');

        return $mockFileLocator;
    }

    /**
     * Get mock procedure instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Procedure\Procedure
     */
    protected function getProcedure()
    {
        $mockProcedure = $this->getMockBuilder('\JonnyW\PhantomJs\Procedure\Procedure')
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        return $mockProcedure;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++ UTILITIES ++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Set up test environment.
     *
     * @access public
     * @return void
     */
    public function setUp()
    {
        $this->filename  = 'test.proc';
        $this->directory = sys_get_temp_dir();

        if (!is_writable($this->directory)) {
            throw new \RuntimeException(sprintf('Test directory must be writable: %s', $this->directory));
        }
    }

    /**
     * Tear down test environment.
     *
     * @access public
     * @return void
     */
    public function tearDown()
    {
        $filename = $this->getFilename();

        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    /**
     * Get test filename.
     *
     * @access public
     * @return string
     */
    public function getFilename()
    {
        return sprintf('%1$s/%2$s', $this->directory, $this->filename);
    }

    /**
     * Write procedure body to file.
     *
     * @access public
     * @param  string $data
     * @return string
     */
    public function writeProcedure($procedure)
    {
        $filename = $this->getFilename();

        file_put_contents($filename, $procedure);

        return $filename;
    }
}

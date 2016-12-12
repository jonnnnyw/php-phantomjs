<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Tests\Unit\Procedure;

use Twig_Environment;
use Twig_Loader_String;
use Symfony\Component\Config\FileLocatorInterface;
use JonnyW\PhantomJs\Engine;
use JonnyW\PhantomJs\Cache\FileCache;
use JonnyW\PhantomJs\Parser\JsonParser;
use JonnyW\PhantomJs\Template\TemplateRenderer;
use JonnyW\PhantomJs\Procedure\ProcedureFactory;
use JonnyW\PhantomJs\Procedure\ProcedureFactoryInterface;
use JonnyW\PhantomJs\Procedure\ProcedureLoader;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test filename.
     *
     * @var string
     */
    protected $filename;

    /**
     * Test directory.
     *
     * @var string
     */
    protected $directory;

    /** +++++++++++++++++++++++++++++++++++ **/
    /** ++++++++++++++ TESTS ++++++++++++++ **/
    /** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test invalid argument exception is thrown if procedure
     * file is not local.
     */
    public function testInvalidArgumentExceptionIsThrownIfProcedureFileIsNotLocal()
    {
        $this->setExpectedException('\InvalidArgumentException');

        $procedureFactory = $this->getProcedureFactory();
        $fileLocator = $this->getFileLocator();

        $fileLocator->method('locate')
            ->will($this->returnValue('http://example.com/index.html'));

        $procedureLoader = $this->getProcedureLoader($procedureFactory, $fileLocator);
        $procedureLoader->load('test');
    }

    /**
     * Test load throws not exists exception if
     * if procedure file does not exist.
     */
    public function testNotExistsExceptionIsThrownIfProcedureFileDoesNotExist()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotExistsException');

        $procedureFactory = $this->getProcedureFactory();
        $fileLocator = $this->getFileLocator();

        $fileLocator->method('locate')
            ->will($this->returnValue('/invalid/file.proc'));

        $procedureLoader = $this->getProcedureLoader($procedureFactory, $fileLocator);
        $procedureLoader->load('test');
    }

    /**
     * Test procedure can be laoded.
     */
    public function testProcedureCanBeLoaded()
    {
        $body = 'TEST_PROCEDURE';
        $file = $this->writeProcedure($body);

        $procedureFactory = $this->getProcedureFactory();
        $fileLocator = $this->getFileLocator();

        $fileLocator->method('locate')
            ->will($this->returnValue($file));

        $procedureLoader = $this->getProcedureLoader($procedureFactory, $fileLocator);

        $this->assertInstanceOf('\JonnyW\PhantomJs\Procedure\ProcedureInterface', $procedureLoader->load('test'));
    }

    /**
     * Test procedure template is set in procedure
     * instance.
     */
    public function testProcedureTemplateIsSetInProcedureInstance()
    {
        $body = 'TEST_PROCEDURE';
        $file = $this->writeProcedure($body);

        $procedureFactory = $this->getProcedureFactory();
        $fileLocator = $this->getFileLocator();

        $fileLocator->method('locate')
            ->will($this->returnValue($file));

        $procedureLoader = $this->getProcedureLoader($procedureFactory, $fileLocator);

        $this->assertSame($body, $procedureLoader->load('test')->getTemplate());
    }

    /**
     * Test procedure template can be loaded.
     */
    public function testProcedureTemplateCanBeLoaded()
    {
        $body = 'TEST_PROCEDURE';
        $file = $this->writeProcedure($body);

        $procedureFactory = $this->getProcedureFactory();
        $fileLocator = $this->getFileLocator();

        $fileLocator->method('locate')
            ->will($this->returnValue($file));

        $procedureLoader = $this->getProcedureLoader($procedureFactory, $fileLocator);

        $this->assertNotNull($procedureLoader->loadTemplate('test'));
    }

    /** +++++++++++++++++++++++++++++++++++ **/
    /** ++++++++++ TEST ENTITIES ++++++++++ **/
    /** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure loader instance.
     *
     * @param \JonnyW\PhantomJs\Procedure\ProcedureFactoryInterface $procedureFactory
     * @param \Symfony\Component\Config\FileLocatorInterface        $locator
     *
     * @return \JonnyW\PhantomJs\Procedure\ProcedureLoader
     */
    protected function getProcedureLoader(ProcedureFactoryInterface $procedureFactory, FileLocatorInterface $locator)
    {
        $procedureLoader = new ProcedureLoader($procedureFactory, $locator);

        return $procedureLoader;
    }

    /**
     * Get procedure factory instance.
     *
     * @param \JonnyW\PhantomJs\Parser\ParserInterface             $parser
     * @param \JonnyW\PhantomJs\Cache\CacheInterface               $cacheHandler
     * @param \JonnyW\PhantomJs\Template\TemplateRendererInterface $renderer
     *
     * @return \JonnyW\PhantomJs\Procedure\ProcedureFactory
     */
    protected function getProcedureFactory()
    {
        $engine = $this->getEngine();
        $parser = $this->getParser();
        $cache = $this->getCache();
        $renderer = $this->getRenderer();

        $procedureFactory = new ProcedureFactory($engine, $parser, $cache, $renderer);

        return $procedureFactory;
    }

    /**
     * Get engine.
     *
     * @return \JonnyW\PhantomJs\Engine
     */
    protected function getEngine()
    {
        $engine = new Engine();

        return $engine;
    }

    /**
     * Get parser.
     *
     * @return \JonnyW\PhantomJs\Parser\JsonParser
     */
    protected function getParser()
    {
        $parser = new JsonParser();

        return $parser;
    }

    /**
     * Get cache.
     *
     * @param string $cacheDir  (default: '')
     * @param string $extension (default: 'proc')
     *
     * @return \JonnyW\PhantomJs\Cache\FileCache
     */
    protected function getCache($cacheDir = '', $extension = 'proc')
    {
        $cache = new FileCache(($cacheDir ? $cacheDir : sys_get_temp_dir()), 'proc');

        return $cache;
    }

    /**
     * Get template renderer.
     *
     * @return \JonnyW\PhantomJs\Template\TemplateRenderer
     */
    protected function getRenderer()
    {
        $twig = new Twig_Environment(
            new Twig_Loader_String()
        );

        $renderer = new TemplateRenderer($twig);

        return $renderer;
    }

    /** +++++++++++++++++++++++++++++++++++ **/
    /** ++++++++++ MOCKS / STUBS ++++++++++ **/
    /** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get file locator.
     *
     * @return \Symfony\Component\Config\FileLocatorInterface
     */
    protected function getFileLocator()
    {
        $fileLocator = $this->getMock('\Symfony\Component\Config\FileLocatorInterface');

        return $fileLocator;
    }

    /** +++++++++++++++++++++++++++++++++++ **/
    /** ++++++++++++ UTILITIES ++++++++++++ **/
    /** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Set up test environment.
     */
    public function setUp()
    {
        $this->filename = 'test.proc';
        $this->directory = sys_get_temp_dir();

        if (!is_writable($this->directory)) {
            throw new \RuntimeException(sprintf('Test directory must be writable: %s', $this->directory));
        }
    }

    /**
     * Tear down test environment.
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
     * @return string
     */
    public function getFilename()
    {
        return sprintf('%1$s/%2$s', $this->directory, $this->filename);
    }

    /**
     * Write procedure body to file.
     *
     * @param string $data
     *
     * @return string
     */
    public function writeProcedure($procedure)
    {
        $filename = $this->getFilename();

        file_put_contents($filename, $procedure);

        return $filename;
    }
}

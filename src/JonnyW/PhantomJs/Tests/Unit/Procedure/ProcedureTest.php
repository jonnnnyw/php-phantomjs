<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Unit\Procedure;

use JonnyW\PhantomJs\Cache\CacheInterface;
use JonnyW\PhantomJs\Parser\ParserInterface;
use JonnyW\PhantomJs\Template\TemplateRendererInterface;
use JonnyW\PhantomJs\Procedure\Procedure;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test load procedure sets procedure
     * body in procedure instance.
     *
     * @access public
     * @return void
     */
    public function testLoadProcedureSetsProcedureBodyInProcedureInstance()
    {
        $template = 'TEST PRODCEDURE';

        $parser    = $this->getParser();
        $cache     = $this->getCache();
        $renderer  = $this->getRenderer();

        $procedure = $this->getProcedure($parser, $cache, $renderer);
        $procedure->load($template);

        $this->assertSame($procedure->getProcedure(), $template);
    }

    /**
     * Test run throws note writeable exception
     * if procedure executable file cannot
     * be written.
     *
     * @access public
     * @return void
     */
    public function testRunThrowsNotWriteableExceptionIfProcedureExecutableFileCannotBeWritten()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotWritableException');

        $parser   = $this->getParser();
        $renderer = $this->getRenderer();

        $cache = $this->getCache();
        $cache->expects($this->once())
            ->method('save')
            ->will($this->throwException(new \JonnyW\PhantomJs\Exception\NotWritableException()));

        $client   = $this->getClient();
        $request  = $this->getRequest();
        $response = $this->getResponse();

        $procedure = $this->getProcedure($parser, $cache, $renderer);
        $procedure->run($client, $request, $response);
    }

    /**
     * Test run throws procedure failed exception
     * if an exception is encountered.
     *
     * @access public
     * @return void
     */
    public function testRunThrowsProcedureFailedExceptionIfAnExceptionIsEncountered()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\ProcedureFailedException');

        $parser = $this->getParser();
        $cache  = $this->getCache();

        $renderer = $this->getRenderer();
        $renderer->expects($this->once())
            ->method('render')
            ->will($this->throwException(new \Exception()));

        $client   = $this->getClient();
        $request  = $this->getRequest();
        $response = $this->getResponse();

        $procedure = $this->getProcedure($parser, $cache, $renderer);
        $procedure->run($client, $request, $response);
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure instance.
     *
     * @access protected
     * @param  \JonnyW\PhantomJs\Parser\ParserInterface             $parser
     * @param  \JonnyW\PhantomJs\Cache\CacheInterface               $cacheHandler
     * @param  \JonnyW\PhantomJs\Template\TemplateRendererInterface $renderer
     * @return \JonnyW\PhantomJs\Procedure\Procedure
     */
    protected function getProcedure(ParserInterface $parser, CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $procedure = new Procedure($parser, $cacheHandler, $renderer);

        return $procedure;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ MOCKS / STUBS ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get mock parser instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Parser\ParserInterface
     */
    protected function getParser()
    {
        $mockParser = $this->getMock('\JonnyW\PhantomJs\Parser\ParserInterface');

        return $mockParser;
    }

    /**
     * Get mock cache instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Cache\CacheInterface
     */
    protected function getCache()
    {
        $mockCache = $this->getMock('\JonnyW\PhantomJs\Cache\CacheInterface');

        return $mockCache;
    }

    /**
     * Get mock template renderer instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Template\TemplateRendererInterface
     */
    protected function getRenderer()
    {
        $mockTemplateRenderer = $this->getMock('\JonnyW\PhantomJs\Template\TemplateRendererInterface');

        return $mockTemplateRenderer;
    }

    /**
     * Get mock client instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\ClientInterface
     */
    protected function getClient()
    {
        $mockClient = $this->getMock('\JonnyW\PhantomJs\ClientInterface');

        return $mockClient;
    }

    /**
     * Get mock request instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Message\RequestInterface
     */
    protected function getRequest()
    {
        $mockRequest = $this->getMock('\JonnyW\PhantomJs\Message\RequestInterface');

        return $mockRequest;
    }

    /**
     * Get mock response instance.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Message\ResponseInterface
     */
    protected function getResponse()
    {
        $mockResponse = $this->getMock('\JonnyW\PhantomJs\Message\ResponseInterface');

        return $mockResponse;
    }
}

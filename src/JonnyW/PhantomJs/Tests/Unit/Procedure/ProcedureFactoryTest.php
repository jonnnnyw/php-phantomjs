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
use JonnyW\PhantomJs\Procedure\ProcedureFactory;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureFactoryTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test create procedure returns instance
     * of procedure.
     *
     * @access public
     * @return void
     */
    public function testCreateProcedureReturnsInstanceOfProcedure()
    {
        $parser    = $this->getParser();
        $cache     = $this->getCache();
        $renderer  = $this->getRenderer();

        $procedureFactory = $this->getProcedureFactory($parser, $cache, $renderer);

        $this->assertInstanceOf('\JonnyW\PhantomJs\Procedure\Procedure', $procedureFactory->createProcedure());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure factory instance.
     *
     * @access protected
     * @param  \JonnyW\PhantomJs\Parser\ParserInterface             $parser
     * @param  \JonnyW\PhantomJs\Cache\CacheInterface               $cacheHandler
     * @param  \JonnyW\PhantomJs\Template\TemplateRendererInterface $renderer
     * @return \JonnyW\PhantomJs\Procedure\ProcedureFactory
     */
    protected function getProcedureFactory(ParserInterface $parser, CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $procedureFactory = new ProcedureFactory($parser, $cacheHandler, $renderer);

        return $procedureFactory;
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
}

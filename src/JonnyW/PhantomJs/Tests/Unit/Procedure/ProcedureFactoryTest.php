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
use JonnyW\PhantomJs\Engine;
use JonnyW\PhantomJs\Cache\FileCache;
use JonnyW\PhantomJs\Cache\CacheInterface;
use JonnyW\PhantomJs\Parser\JsonParser;
use JonnyW\PhantomJs\Parser\ParserInterface;
use JonnyW\PhantomJs\Template\TemplateRenderer;
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
     * Test factory can create instance of
     * procedure.
     *
     * @access public
     * @return void
     */
    public function testFactoryCanCreateInstanceOfProcedure()
    {
        $engine    = $this->getEngine();
        $parser    = $this->getParser();
        $cache     = $this->getCache();
        $renderer  = $this->getRenderer();

        $procedureFactory = $this->getProcedureFactory($engine, $parser, $cache, $renderer);

        $this->assertInstanceOf('\JonnyW\PhantomJs\Procedure\Procedure', $procedureFactory->createProcedure());
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure factory instance.
     *
     * @access protected
     * @param  \JonnyW\PhantomJs\Engine                             $engine
     * @param  \JonnyW\PhantomJs\Parser\ParserInterface             $parser
     * @param  \JonnyW\PhantomJs\Cache\CacheInterface               $cacheHandler
     * @param  \JonnyW\PhantomJs\Template\TemplateRendererInterface $renderer
     * @return \JonnyW\PhantomJs\Procedure\ProcedureFactory
     */
    protected function getProcedureFactory(Engine $engine, ParserInterface $parser, CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $procedureFactory = new ProcedureFactory($engine, $parser, $cacheHandler, $renderer);

        return $procedureFactory;
    }

    /**
     * Get engine.
     *
     * @access protected
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
     * @access protected
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
     * @access protected
     * @param  string                            $cacheDir  (default: '')
     * @param  string                            $extension (default: 'proc')
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
     * @access protected
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
}

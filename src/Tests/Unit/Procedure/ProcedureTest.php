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
use JonnyW\PhantomJs\Procedure\Input;
use JonnyW\PhantomJs\Procedure\Output;
use JonnyW\PhantomJs\Procedure\Procedure;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureTest extends \PHPUnit_Framework_TestCase
{
    /** +++++++++++++++++++++++++++++++++++ **/
    /** ++++++++++++++ TESTS ++++++++++++++ **/
    /** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test procedure template can be
     * set in procedure.
     */
    public function testProcedureTemplateCanBeSetInProcedure()
    {
        $template = 'PROCEDURE_TEMPLATE';

        $engne = $this->getEngine();
        $parser = $this->getParser();
        $cache = $this->getCache();
        $renderer = $this->getRenderer();

        $procedure = $this->getProcedure($engne, $parser, $cache, $renderer);
        $procedure->setTemplate($template);

        $this->assertSame($procedure->getTemplate(), $template);
    }

    /**
     * Test procedure can be compiled.
     */
    public function testProcedureCanBeCompiled()
    {
        $template = 'TEST_{{ input.get("uncompiled") }}_PROCEDURE';

        $engne = $this->getEngine();
        $parser = $this->getParser();
        $cache = $this->getCache();
        $renderer = $this->getRenderer();

        $input = $this->getInput();
        $input->set('uncompiled', 'COMPILED');

        $procedure = $this->getProcedure($engne, $parser, $cache, $renderer);
        $procedure->setTemplate($template);

        $this->assertSame('TEST_COMPILED_PROCEDURE', $procedure->compile($input));
    }

    /**
     * Test not writable exception is thrown if procedure
     * script cannot be written to file.
     */
    public function testNotWritableExceptionIsThrownIfProcedureScriptCannotBeWrittenToFile()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\NotWritableException');

        $engne = $this->getEngine();
        $parser = $this->getParser();
        $renderer = $this->getRenderer();

        $cache = $this->getCache('/an/invalid/dir');

        $input = $this->getInput();
        $output = $this->getOutput();

        $procedure = $this->getProcedure($engne, $parser, $cache, $renderer);
        $procedure->run($input, $output);
    }

    /**
     * Test procedure failed exception is thrown if procedure
     * cannot be run.
     */
    public function testProcedureFailedExceptionIsThrownIfProcedureCannotBeRun()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\ProcedureFailedException');

        $parser = $this->getParser();
        $cache = $this->getCache();
        $renderer = $this->getRenderer();
        $input = $this->getInput();
        $output = $this->getOutput();

        $engne = $this->getEngine();
        $engne->method('getCommand')
            ->will($this->throwException(new \Exception()));

        $procedure = $this->getProcedure($engne, $parser, $cache, $renderer);
        $procedure->run($input, $output);
    }

    /** +++++++++++++++++++++++++++++++++++ **/
    /** ++++++++++ TEST ENTITIES ++++++++++ **/
    /** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure instance.
     *
     * @param \JonnyW\PhantomJs\Engine                             $engine
     * @param \JonnyW\PhantomJs\Parser\ParserInterface             $parser
     * @param \JonnyW\PhantomJs\Cache\CacheInterface               $cacheHandler
     * @param \JonnyW\PhantomJs\Template\TemplateRendererInterface $renderer
     *
     * @return \JonnyW\PhantomJs\Procedure\Procedure
     */
    protected function getProcedure(Engine $engine, ParserInterface $parser, CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $procedure = new Procedure($engine, $parser, $cacheHandler, $renderer);

        return $procedure;
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

    /**
     * Get input.
     *
     * @return \JonnyW\PhantomJs\Procedure\Input
     */
    protected function getInput()
    {
        $input = new Input();

        return $input;
    }

    /**
     * Get output.
     *
     * @return \JonnyW\PhantomJs\Procedure\Output
     */
    protected function getOutput()
    {
        $output = new Output();

        return $output;
    }

    /** +++++++++++++++++++++++++++++++++++ **/
    /** ++++++++++ MOCKS / STUBS ++++++++++ **/
    /** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get engine.
     *
     * @return \JonnyW\PhantomJs\Engine
     */
    protected function getEngine()
    {
        $engine = $this->getMock('\JonnyW\PhantomJs\Engine');

        return $engine;
    }
}

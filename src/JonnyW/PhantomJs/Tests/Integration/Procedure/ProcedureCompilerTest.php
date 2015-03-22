<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Integration\Procedure;

use JonnyW\PhantomJs\Http\Request;
use JonnyW\PhantomJs\Procedure\ProcedureCompiler;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureCompilerTest extends \PHPUnit_Framework_TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test can compile procedure
     *
     * @access public
     * @return void
     */
    public function testCanCompileProcedure()
    {
        $procedure = $this->getProcedure('http_default');

        $uncompiled = $procedure->getTemplate();

        $request = $this->getRequest();
        $request->setUrl('http://test.com');

        $compiler = $this->getProcedureCompiler();
        $compiler->compile($procedure, $request);

        $this->assertNotSame($uncompiled, $procedure->getTemplate());
    }

    /**
     * Test procedure is loaded from cache
     * if cache is enabled.
     *
     * @access public
     * @return void
     */
    public function testProcedureIsLoadedFromCacheIfCacheIsEnabled()
    {
        $procedure1 = $this->getProcedure('http_default');
        $procedure2 = $this->getProcedure('http_default');

        $request = $this->getRequest();
        $request->setUrl('http://test.com');

        $renderer = $this->getMock('\JonnyW\PhantomJs\Template\TemplateRendererInterface');
        $renderer->expects($this->exactly(1))
            ->method('render')
            ->will($this->returnValue('var test=1; phantom.exit(1);'));

        $serviceContainer = ServiceContainer::getInstance();

        $compiler = new ProcedureCompiler(
            $serviceContainer->get('phantomjs.procedure.chain_loader'),
            $serviceContainer->get('phantomjs.procedure.procedure_validator'),
            $serviceContainer->get('phantomjs.cache.file_cache'),
            $renderer
        );

        $compiler->enableCache();
        $compiler->compile($procedure1, $request);
        $compiler->compile($procedure2, $request);
    }

    /**
     * Test procedure is not loaded from
     * cache if cache is disabled.
     *
     * @access public
     * @return void
     */
    public function testProcedureIsNotLoadedFromCacheIfCacheIsDisabled()
    {
        $procedure1 = $this->getProcedure('http_default');
        $procedure2 = $this->getProcedure('http_default');

        $request = $this->getRequest();
        $request->setUrl('http://test.com');

        $renderer = $this->getMock('\JonnyW\PhantomJs\Template\TemplateRendererInterface');
        $renderer->expects($this->exactly(2))
            ->method('render')
            ->will($this->returnValue('var test=1; phantom.exit(1);'));

        $serviceContainer = ServiceContainer::getInstance();

        $compiler = new ProcedureCompiler(
            $serviceContainer->get('phantomjs.procedure.chain_loader'),
            $serviceContainer->get('phantomjs.procedure.procedure_validator'),
            $serviceContainer->get('phantomjs.cache.file_cache'),
            $renderer
        );

        $compiler->disableCache();
        $compiler->compile($procedure1, $request);
        $compiler->compile($procedure2, $request);
    }

    /**
     * Test procedure cache can be cleared.
     *
     * @access public
     * @return void
     */
    public function testProcedureCacheCanBeCleared()
    {
        $procedure1 = $this->getProcedure('http_default');
        $procedure2 = $this->getProcedure('http_default');

        $request = $this->getRequest();
        $request->setUrl('http://test.com');

        $renderer = $this->getMock('\JonnyW\PhantomJs\Template\TemplateRendererInterface');
        $renderer->expects($this->exactly(2))
            ->method('render')
            ->will($this->returnValue('var test=1; phantom.exit(1);'));

        $serviceContainer = ServiceContainer::getInstance();

        $compiler = new ProcedureCompiler(
            $serviceContainer->get('phantomjs.procedure.chain_loader'),
            $serviceContainer->get('phantomjs.procedure.procedure_validator'),
            $serviceContainer->get('phantomjs.cache.file_cache'),
            $renderer
        );

        $compiler->compile($procedure1, $request);
        $compiler->clearCache();
        $compiler->compile($procedure2, $request);
    }

    /**
     * Test syntax exception is thrown if compiled
     * template is not valid.
     *
     * @access public
     * @return void
     */
    public function testSyntaxExceptionIsThrownIfCompiledTemplateIsNotValid()
    {
        $this->setExpectedException('\JonnyW\PhantomJs\Exception\SyntaxException');

        $template = <<<EOF
    console.log(;
EOF;
        $procedure = $this->getProcedure('http_default');
        $procedure->setTemplate($template);

        $request = $this->getRequest();
        $request->setUrl('http://test.com');

        $compiler = $this->getProcedureCompiler();
        $compiler->compile($procedure, $request);

    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get procedure compiler.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Procedure\ProcedureCompiler
     */
    protected function getProcedureCompiler()
    {
        $serviceContainer = ServiceContainer::getInstance();

        $compiler = new ProcedureCompiler(
            $serviceContainer->get('phantomjs.procedure.chain_loader'),
            $serviceContainer->get('phantomjs.procedure.procedure_validator'),
            $serviceContainer->get('phantomjs.cache.file_cache'),
            $serviceContainer->get('phantomjs.procedure.template_renderer')
        );

        return $compiler;
    }

    /**
     * getProcedure function.
     *
     * @access protected
     * @param  string                                         $id
     * @return \JonnyW\PhantomJs\Procedure\ProcedureInterface
     */
    protected function getProcedure($id)
    {
        return ServiceContainer::getInstance()->get('procedure_loader')->load($id);
    }

    /**
     * Get request
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Http\Request
     */
    protected function getRequest()
    {
        $request = new Request();

        return $request;
    }

/** +++++++++++++++++++++++++++++++ **/
/** ++++++++++ UTILITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++ **/

    /**
     * Set up tasks.
     *
     * @access protected
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cleanup();
    }

    /**
     * Tear down tasks.
     *
     * @access protected
     * @return void
     */
    protected function tearDown()
    {
        parent::setUp();

        $this->cleanup();
    }

    /**
     * Clean up cache files.
     *
     * @access protected
     * @return void
     */
    protected function cleanup()
    {
        $cache = sprintf('%s/phantomjs_*', sys_get_temp_dir());

        array_map('unlink', glob($cache));
    }
}

<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Tests\Integration\Template;

use JonnyW\PhantomJs\Test\TestCase;
use JonnyW\PhantomJs\Template\TemplateRenderer;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class TemplateRendererTest extends TestCase
{

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++++++ TESTS ++++++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Test render injects single parameter
     * into template.
     *
     * @access public
     * @return void
     */
    public function testRenderInjectsSingleParameterIntoTemplate()
    {
        $template = 'var param = "{{ test }}"';

        $renderer = $this->getInjectedTemplateRenderer();
        $result   = $renderer->render($template, array('test' => 'data'));

        $this->assertSame('var param = "data"', $result);
    }

    /**
     * Test render injects multiple parameters
     * into template.
     *
     * @access public
     * @return void
     */
    public function testRenderInjectsMultipleParametersIntoTemplates()
    {
        $template = 'var param = "{{ test }}", var param2 = "{{ test2 }}"';

        $renderer = $this->getInjectedTemplateRenderer();
        $result   = $renderer->render($template, array('test' => 'data', 'test2' => 'more data'));

        $this->assertSame('var param = "data", var param2 = "more data"', $result);
    }

    /**
     * Test render injects parameter into
     * template using object method.
     *
     * @access public
     * @return void
     */
    public function testRenderInjectsParameterIntoTemplateUsingObjectMethod()
    {
        $template = 'var param = {{ request.getTimeout() }}';

        $request = $this->getRequest();
        $request->method('getTimeout')
            ->will($this->returnValue(5000));

        $renderer = $this->getInjectedTemplateRenderer();
        $result   = $renderer->render($template, array('request' => $request));

        $this->assertSame('var param = 5000', $result);
    }

    /**
     * Test render injects parameter into
     * template using object method
     * with parameter.
     *
     * @access public
     * @return void
     */
    public function testRenderInjectsParameterIntoTemplateUsingObjectMethodWithParameter()
    {
        $template = 'var param = {{ request.getHeaders("json") }}';

        $request = $this->getRequest();
        $request->expects($this->once())
            ->method('getHeaders')
            ->with($this->identicalTo('json'));

        $renderer = $this->getInjectedTemplateRenderer();
        $renderer->render($template, array('request' => $request));
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ TEST ENTITIES ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

    /**
     * Get template renderer instance.
     *
     * @param  \Twig_Environment                          $twig
     * @return \JonnyW\PhantomJs\Message\TemplateRenderer
     */
    protected function getTemplateRenderer(\Twig_Environment $twig)
    {
        $templateRenderer = new TemplateRenderer($twig);

        return $templateRenderer;
    }

    /**
     * Get template renderer instance
     * injected with dependencies.
     *
     * @access protected
     * @return \JonnyW\PhantomJs\Message\TemplateRenderer
     */
    protected function getInjectedTemplateRenderer()
    {
        $twig = $this->getContainer()->get('phantomjs.twig.environment');

        $templateRenderer = $this->getTemplateRenderer($twig);

        return $templateRenderer;
    }

/** +++++++++++++++++++++++++++++++++++ **/
/** ++++++++++ MOCKS / STUBS ++++++++++ **/
/** +++++++++++++++++++++++++++++++++++ **/

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
}

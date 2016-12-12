<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Procedure;

use JonnyW\PhantomJs\Cache\CacheInterface;
use JonnyW\PhantomJs\Template\TemplateRendererInterface;
use JonnyW\PhantomJs\IO\InputInterface;
use JonnyW\PhantomJs\IO\OutputInterface;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class ProcedureCompiler implements ProcedureCompilerInterface
{
    /**
     * Procedure loader.
     *
     * @var \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    protected $procedureLoader;

    /**
     * Procedure validator.
     *
     * @var \JonnyW\PhantomJs\Procedure\ProcedureValidatorInterface
     */
    protected $procedureValidator;

    /**
     * Cache handler.
     *
     * @var \JonnyW\PhantomJs\Cache\CacheInterface
     */
    protected $cacheHandler;

    /**
     * Renderer.
     *
     * @var \JonnyW\PhantomJs\Template\TemplateRendererInterface
     */
    protected $renderer;

    /**
     * Cache enabled.
     *
     * @var bool
     */
    protected $cacheEnabled;

    /**
     * Internal constructor.
     *
     * @param \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface    $procedureLoader
     * @param \JonnyW\PhantomJs\Procedure\ProcedureValidatorInterface $procedureValidator
     * @param \JonnyW\PhantomJs\Cache\CacheInterface                  $cacheHandler
     * @param \JonnyW\PhantomJs\Template\TemplateRendererInterface    $renderer
     */
    public function __construct(ProcedureLoaderInterface $procedureLoader, ProcedureValidatorInterface $procedureValidator,
        CacheInterface $cacheHandler, TemplateRendererInterface $renderer)
    {
        $this->procedureLoader = $procedureLoader;
        $this->procedureValidator = $procedureValidator;
        $this->cacheHandler = $cacheHandler;
        $this->renderer = $renderer;
        $this->cacheEnabled = true;
    }

    /**
     * Compile partials into procedure.
     *
     * @param \JonnyW\PhantomJs\Procedure\ProcedureInterface $procedure
     * @param \JonnyW\PhantomJs\IO\InputInterface            $input
     * @param \JonnyW\PhantomJs\IO\OutputInterface           $output
     */
    public function compile(ProcedureInterface $procedure, InputInterface $input, OutputInterface $output)
    {
        $cacheKey = sprintf('phantomjs_%s_%s_%s', $input->getType(), $output->getType(), md5($procedure->getTemplate()));

        if ($this->cacheEnabled && $this->cacheHandler->exists($cacheKey)) {
            $template = $this->cacheHandler->fetch($cacheKey);
        }

        if (empty($template)) {
            $template = $this->renderer
                ->render($procedure->getTemplate(), array('engine' => $this, 'procedure_type' => $output->getType()));

            $test = clone $procedure;
            $test->setTemplate($template);

            $compiled = $test->compile($input, $output);

            $this->procedureValidator->validate($compiled);

            if ($this->cacheEnabled) {
                $this->cacheHandler->save($cacheKey, $template);
            }
        }

        $procedure->setTemplate($template);
    }

    /**
     * Load partial template.
     *
     * @param string $name
     *
     * @return string
     */
    public function load($name)
    {
        return $this->procedureLoader->loadTemplate($name, 'partial');
    }

    /**
     * Enable cache.
     */
    public function enableCache()
    {
        $this->cacheEnabled = true;
    }

    /**
     * Disable cache.
     */
    public function disableCache()
    {
        $this->cacheEnabled = false;
    }

    /**
     * Clear cache.
     */
    public function clearCache()
    {
        $this->cacheHandler->delete('phantomjs_*');
    }
}

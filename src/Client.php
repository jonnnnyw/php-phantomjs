<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs;

use JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface;
use JonnyW\PhantomJs\Procedure\ProcedureCompilerInterface;
use JonnyW\PhantomJs\IO\InputInterface;
use JonnyW\PhantomJs\IO\OutputInterface;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Client implements ClientInterface
{
    /**
     * Client.
     *
     * @var \JonnyW\PhantomJs\Client\ClientInterface
     */
    protected static $instance;

    /**
     * PhantomJs engine.
     *
     * @var \JonnyW\PhantomJs\Engine
     */
    protected $engine;

    /**
     * Procedure loader.
     *
     * @var \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    protected $procedureLoader;

    /**
     * Procedure validator.
     *
     * @var \JonnyW\PhantomJs\Procedure\ProcedureCompilerInterface
     */
    protected $procedureCompiler;

    /**
     * Procedure template.
     *
     * @var string
     */
    protected $procedure;

    /**
     * Internal constructor.
     *
     * @param \JonnyW\PhantomJs\Engine                               $engine
     * @param \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface   $procedureLoader
     * @param \JonnyW\PhantomJs\Procedure\ProcedureCompilerInterface $procedureCompiler
     */
    public function __construct(Engine $engine, ProcedureLoaderInterface $procedureLoader, ProcedureCompilerInterface $procedureCompiler)
    {
        $this->engine = $engine;
        $this->procedureLoader = $procedureLoader;
        $this->procedureCompiler = $procedureCompiler;
        $this->procedure = 'default';
    }

    /**
     * Get singleton instance.
     *
     * @return \JonnyW\PhantomJs\Client\ClientInterface
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof ClientInterface) {
            $serviceContainer = ServiceContainer::getInstance();

            self::$instance = new static(
                $serviceContainer->get('engine'),
                $serviceContainer->get('procedure_loader'),
                $serviceContainer->get('procedure_compiler')
            );
        }

        return self::$instance;
    }

    /**
     * Run client.
     *
     * @param \JonnyW\PhantomJs\IO\InputInterface  $input
     * @param \JonnyW\PhantomJs\IO\OutputInterface $output
     *
     * @return \JonnyW\PhantomJs\IO\OutputInterface
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $procedure = $this->procedureLoader->load($this->procedure);

        $this->procedureCompiler->compile($procedure, $input);

        $procedure->run($input, $output);

        return $output;
    }

    /**
     * Get PhantomJs engine.
     *
     * @return \JonnyW\PhantomJs\Engine
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * Get procedure loader instance.
     *
     * @return \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    public function getProcedureLoader()
    {
        return $this->procedureLoader;
    }

    /**
     * Get procedure compiler.
     *
     * @return \JonnyW\PhantomJs\Procedure\ProcedureCompilerInterface
     */
    public function getProcedureCompiler()
    {
        return $this->procedureCompiler;
    }

    /**
     * Set procedure template.
     *
     * @param string $procedure
     */
    public function setProcedure($procedure)
    {
        $this->procedure = $procedure;
    }

    /**
     * Get procedure template.
     *
     * @return string
     */
    public function getProcedure()
    {
        return $this->procedure;
    }

    /**
     * Get log.
     *
     * @return string
     */
    public function getLog()
    {
        return $this->getEngine()->getLog();
    }
}

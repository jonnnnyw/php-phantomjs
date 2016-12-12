<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs;

use JonnyW\PhantomJs\IO\InputInterface;
use JonnyW\PhantomJs\IO\OutputInterface;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface ClientInterface
{
    /**
     * Get singleton instance.
     *
     * @return \JonnyW\PhantomJs\Client\ClientInterface
     */
    public static function getInstance();

    /**
     * Get engine instance.
     *
     * @return \JonnyW\PhantomJs\Engine
     */
    public function getEngine();

    /**
     * Get procedure loader instance.
     *
     * @return \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    public function getProcedureLoader();

    /**
     * Get procedure compiler.
     *
     * @return \JonnyW\PhantomJs\Procedure\ProcedureCompilerInterface
     */
    public function getProcedureCompiler();

    /**
     * Set procedure template.
     *
     * @param string $procedure
     */
    public function setProcedure($procedure);

    /**
     * Get procedure template.
     *
     * @return string
     */
    public function getProcedure();

    /**
     * Run client.
     *
     * @param \JonnyW\PhantomJs\IO\InputInterface  $input
     * @param \JonnyW\PhantomJs\IO\OutputInterface $output
     */
    public function run(InputInterface $input, OutputInterface $output);

    /**
     * Get log.
     *
     * @return string
     */
    public function getLog();
}

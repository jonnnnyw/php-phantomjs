<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Procedure;

use JonnyW\PhantomJs\IO\InputInterface;
use JonnyW\PhantomJs\IO\OutputInterface;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface ProcedureCompilerInterface
{
    /**
     * Compile partials into procedure.
     *
     * @param \JonnyW\PhantomJs\Procedure\ProcedureInterface $procedure
     * @param \JonnyW\PhantomJs\IO\InputInterface            $input
     * @param \JonnyW\PhantomJs\IO\OutputInterface           $output
     */
    public function compile(ProcedureInterface $procedure, InputInterface $input, OutputInterface $output);

    /**
     * Load partial template.
     *
     * @param string $name
     *
     * @return string
     */
    public function load($name);

    /**
     * Enable cache.
     */
    public function enableCache();

    /**
     * Disable cache.
     */
    public function disableCache();

    /**
     * Clear cache.
     */
    public function clearCache();
}

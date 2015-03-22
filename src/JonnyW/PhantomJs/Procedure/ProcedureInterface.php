<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Procedure;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface ProcedureInterface
{
    /**
     * Run procedure.
     *
     * @access public
     * @param \JonnyW\PhantomJs\Procedure\InputInterface  $input
     * @param \JonnyW\PhantomJs\Procedure\OutputInterface $output
     */
    public function run(InputInterface $input, OutputInterface $output);

    /**
     * Set procedure template.
     *
     * @access public
     * @param string $template
     */
    public function setTemplate($template);

    /**
     * Get procedure template.
     *
     * @access public
     * @return string
     */
    public function getTemplate();

    /**
     * Compile procedure.
     *
     * @access public
     * @param  \JonnyW\PhantomJs\Procedure\InputInterface $input
     * @return string
     */
    public function compile(InputInterface $input);
}

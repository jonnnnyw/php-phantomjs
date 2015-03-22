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
interface ProcedureLoaderInterface
{
    /**
     * Load procedure instance by id.
     *
     * @access public
     * @param  string                                         $id
     * @return \JonnyW\PhantomJs\Procedure\ProcedureInterface
     */
    public function load($id);

    /**
     * Load procedure template by id.
     *
     * @access public
     * @param  string $id
     * @param  string $extension (default: 'proc')
     * @return string
     */
    public function loadTemplate($id, $extension = 'proc');
}

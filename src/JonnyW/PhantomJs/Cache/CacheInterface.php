<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Cache;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface CacheInterface
{
    /**
     * Write data to storage.
     *
     * @access public
     * @param  string $id
     * @param  string $data
     * @return string
     */
    public function save($id, $data);

    /**
     * Fetch data from file.
     *
     * @access public
     * @param  string $id
     * @return void
     */
    public function fetch($id);

    /**
     * Delete data from storage.
     *
     * @access public
     * @param  string $id
     * @return void
     */
    public function delete($id);

    /**
     * Data exists in storage.
     *
     * @access public
     * @param  string  $id
     * @return boolean
     */
    public function exists($id);
}

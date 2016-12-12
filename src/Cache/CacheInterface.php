<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs\Cache;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface CacheInterface
{
    /**
     * Write data to storage.
     *
     * @param string $id
     * @param string $data
     *
     * @return string
     */
    public function save($id, $data);

    /**
     * Fetch data from file.
     *
     * @param string $id
     */
    public function fetch($id);

    /**
     * Delete data from storage.
     *
     * @param string $id
     */
    public function delete($id);

    /**
     * Data exists in storage.
     *
     * @param string $id
     *
     * @return bool
     */
    public function exists($id);
}

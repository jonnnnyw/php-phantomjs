<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Message;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface FactoryInterface
{
    /**
     * Get singleton instance
     *
     * @return Factory
     */
    public static function getInstance();

    /**
     * Create request instance
     *
     * @param  string                                     $method
     * @param  string                                     $url
     * @return \JonnyW\PhantomJs\Message\RequestInterface
     */
    public function createRequest($method = RequestInterface::METHOD_GET, $url);

    /**
     * Create response instance
     *
     * @return \JonnyW\PhantomJs\Message\ResoibseInterface
     */
    public function createResponse();
}

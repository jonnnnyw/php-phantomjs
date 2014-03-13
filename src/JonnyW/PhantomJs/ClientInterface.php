<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs;

use JonnyW\PhantomJs\Message\RequestInterface;
use JonnyW\PhantomJs\Message\ResponseInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface ClientInterface
{
    /**
     * Send request
     *
     * @param  \JonnyW\PhantomJs\Message\RequestInterface  $request
     * @param  \JonnyW\PhantomJs\Message\ResponseInterface $response
     * @param  string                                      $file
     * @return \JonnyW\PhantomJs\Message\ResponseInterface
     */
    public function send(RequestInterface $request, ResponseInterface $response, $file = null);

    /**
     * Open page
     *
     * @param  \JonnyW\PhantomJs\Message\RequestInterface  $request
     * @param  \JonnyW\PhantomJs\Message\ResponseInterface $response
     * @return \JonnyW\PhantomJs\Message\ResponseInterface
     */
    public function open(RequestInterface $request, ResponseInterface $response);

    /**
     * Screen capture
     *
     * @param  \JonnyW\PhantomJs\Message\RequestInterface  $request
     * @param  \JonnyW\PhantomJs\Message\ResponseInterface $response
     * @param  string                                      $file
     * @return \JonnyW\PhantomJs\Message\ResponseInterface
     */
    public function capture(RequestInterface $request, ResponseInterface $response, $file);

    /**
     * Set new PhantomJs path
     *
     * @param  string                   $path
     * @return \JonnyW\PhantomJs\Client
     */
    public function setPhantomJs($path);
}

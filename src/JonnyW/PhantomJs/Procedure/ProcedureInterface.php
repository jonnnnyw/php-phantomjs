<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Procedure;

use JonnyW\PhantomJs\ClientInterface;
use JonnyW\PhantomJs\Message\RequestInterface;
use JonnyW\PhantomJs\Message\ResponseInterface;

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
     * @param \JonnyW\PhantomJs\ClientInterface           $client
     * @param \JonnyW\PhantomJs\Message\RequestInterface  $request
     * @param \JonnyW\PhantomJs\Message\ResponseInterface $response
     */
    public function run(ClientInterface $client, RequestInterface $request, ResponseInterface $response);

    /**
     * Load procedure.
     *
     * @access public
     * @param string $procedure
     */
    public function load($procedure);

    /**
     * Get procedure template.
     *
     * @access public
     * @return string
     */
    public function getProcedure();
}

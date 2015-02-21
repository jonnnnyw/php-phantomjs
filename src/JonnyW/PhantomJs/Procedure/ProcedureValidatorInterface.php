<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Procedure;

use JonnyW\PhantomJs\ClientInterface;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
interface ProcedureValidatorInterface
{
    /**
     * Validate procedure.
     *
     * @access public
     * @param  \JonnyW\PhantomJs\ClientInterface              $client
     * @param  \JonnyW\PhantomJs\Procedure\ProcedureInterface $procedure
     * @param  \JonnyW\PhantomJs\Procedure\InputInterface     $message
     * @return boolean
     */
    public function validate(ClientInterface $client, ProcedureInterface $procedure, InputInterface $message);
}

<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs\Exception;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class SyntaxException extends PhantomJsException
{
    /**
     * Error storage.
     *
     * @var array
     * @access protected
     */
    protected $errors;

    /**
     * Internal constructor.
     *
     * @access public
     * @param  string $exception
     * @param  array  $errors    (default: array())
     * @return void
     */
    public function __construct($exception, array $errors = array())
    {
        parent::__construct($exception);

        $this->errors = $errors;
    }

    /**
     * Get errors.
     *
     * @access public
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}

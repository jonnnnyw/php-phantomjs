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
class Input implements InputInterface
{
    /**
     * Data storage.
     *
     * @var array
     * @access protected
     */
    protected $data;

    /**
     * Internal constructor.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->data = array();
    }

    /**
     * Set data value.
     *
     * @access public
     * @param  string                            $name
     * @param  mixed                             $value
     * @return \JonnyW\PhantomJs\Procedure\Input
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }

    /**
     * Get data value.
     *
     * @access public
     * @param  string $name
     * @return mixed
     */
    public function get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return '';
    }
}

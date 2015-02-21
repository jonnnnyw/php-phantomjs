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
class Output implements OutputInterface
{
    /**
     * Output data.
     *
     * @var array
     * @access protected
     */
    protected $data;

    /**
     * Output logs.
     *
     * @var array
     * @access protected
     */
    protected $logs;

    /**
     * Internal constructor.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->data = array();
        $this->logs = array();
    }

    /**
     * Import data.
     *
     * @param array $data
     * @access public
     */
    public function import(array $data)
    {
        $this->data = $data;
    }

    /**
     * Set data value.
     *
     * @access public
     * @param  string                             $name
     * @param  mixed                              $value
     * @return \JonnyW\PhantomJs\Procedure\Output
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

    /**
     * Log data.
     *
     * @access public
     * @param string $data
     */
    public function log($data)
    {
        $this->logs[] = $data;
    }

    /**
     * Get log data.
     *
     * @access public
     * @return array
     */
    public function getLogs()
    {
        return $this->logs;
    }
}

<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace JonnyW\PhantomJs;

use JonnyW\PhantomJs\Exception\InvalidExecutableException;

/**
 * PHP PhantomJs.
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Engine
{
    /**
     * Executable path.
     *
     * @var string
     */
    protected $path;

    /**
     * Debug flag.
     *
     * @var bool
     */
    protected $debug;

    /**
     * Cache flag.
     *
     * @var bool
     */
    protected $cache;

    /**
     * PhantomJs run options.
     *
     * @var array
     */
    protected $options;

    /**
     * Log info.
     *
     * @var string
     */
    protected $log;

    /**
     * Internal constructor.
     */
    public function __construct()
    {
        $this->path = 'bin'.DIRECTORY_SEPARATOR.'phantomjs';
        $this->options = array();

        $this->debug = false;
        $this->cache = true;
    }

    /**
     * Get PhantomJs run command with
     * loader run options.
     *
     * @return string
     */
    public function getCommand()
    {
        $path = $this->getPath();
        $options = $this->getOptions();

        $this->validateExecutable($path);

        if ($this->cache) {
            array_push($options, '--disk-cache=true');
        }

        if ($this->debug) {
            array_push($options, '--debug=true');
        }

        return sprintf('%s %s', $path, implode(' ', $options));
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return \JonnyW\PhantomJs\Client
     */
    public function setPath($path)
    {
        $this->validateExecutable($path);

        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set PhantomJs run options.
     *
     * @param array $options
     *
     * @return \JonnyW\PhantomJs\Client
     */
    public function setOptions(array $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get PhantomJs run options.
     *
     * @return array
     */
    public function getOptions()
    {
        return (array) $this->options;
    }

    /**
     * Add single PhantomJs run option.
     *
     * @param string $option
     *
     * @return \JonnyW\PhantomJs\Client
     */
    public function addOption($option)
    {
        if (!in_array($option, $this->options)) {
            $this->options[] = $option;
        }

        return $this;
    }

    /**
     * Debug.
     *
     * @param bool $doDebug
     *
     * @return \JonnyW\PhantomJs\Client
     */
    public function debug($doDebug)
    {
        $this->debug = $doDebug;

        return $this;
    }

    /**
     * Cache.
     *
     * @param bool $doCache
     *
     * @return \JonnyW\PhantomJs\Client
     */
    public function cache($doCache)
    {
        $this->cache = $doCache;

        return $this;
    }

    /**
     * Log info.
     *
     * @param string $info
     *
     * @return \JonnyW\PhantomJs\Client
     */
    public function log($info)
    {
        $this->log = $info;

        return $this;
    }

    /**
     * Get log info.
     *
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Clear log info.
     *
     * @return \JonnyW\PhantomJs\Client
     */
    public function clearLog()
    {
        $this->log = '';

        return $this;
    }

    /**
     * Validate execuable file.
     *
     * @param string $file
     *
     * @return bool
     *
     * @throws \JonnyW\PhantomJs\Exception\InvalidExecutableException
     */
    private function validateExecutable($file)
    {
        if (!file_exists($file) || !is_executable($file)) {
            throw new InvalidExecutableException(sprintf('File does not exist or is not executable: %s', $file));
        }

        return true;
    }
}

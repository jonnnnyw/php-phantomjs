<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs;

use JonnyW\PhantomJs\Exception\InvalidExecutableException;
use JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface;
use JonnyW\PhantomJs\Message\MessageFactoryInterface;
use JonnyW\PhantomJs\Message\RequestInterface;
use JonnyW\PhantomJs\Message\ResponseInterface;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Client implements ClientInterface
{
    /**
     * Client instance
     *
     * @var \JonnyW\PhantomJs\ClientInterface
     * @access private
     */
    private static $instance;

    /**
     * Procedure loader instance
     *
     * @var \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     * @access protected
     */
    protected $procedureLoader;

    /**
     * Message factory instance
     *
     * @var \JonnyW\PhantomJs\Message\MessageFactoryInterface
     * @access protected
     */
    protected $messageFactory;

    /**
     * Bin directory path.
     *
     * @var string
     * @access protected
     */
    protected $binDir;

    /**
     * Path to PhantomJs executable
     *
     * @var string
     * @access protected
     */
    protected $phantomJs;

    /**
     * Path to PhantomJs loader executable
     *
     * @var string
     * @access protected
     */
    protected $phantomLoader;

    /**
     * Debug.
     *
     * @var boolean
     * @access protected
     */
    protected $debug;

    /**
     * Log info
     *
     * @var array
     * @access protected
     */
    protected $log;

    /**
     * PhantomJs run options
     *
     * @var mixed
     * @access protected
     */
    protected $options;

    /**
     * Internal constructor
     *
     * @access public
     * @param \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface $procedureLoader
     * @param \JonnyW\PhantomJs\Message\MessageFactoryInterface    $messageFactory
     */
    public function __construct(ProcedureLoaderInterface $procedureLoader, MessageFactoryInterface $messageFactory)
    {
        $this->procedureLoader = $procedureLoader;
        $this->messageFactory  = $messageFactory;
        $this->binDir          = 'bin';
        $this->phantomJs       = '%s/phantomjs';
        $this->phantomLoader   = '%s/phantomloader';
        $this->options         = array();
    }

    /**
     * Get singleton instance
     *
     * @access public
     * @return \JonnyW\PhantomJs\Client
     */
    public static function getInstance()
    {
        if (!self::$instance instanceof ClientInterface) {

            $serviceContainer = ServiceContainer::getInstance();

            self::$instance = new Client(
                $serviceContainer->get('procedure_loader'),
                $serviceContainer->get('message_factory')
            );
        }

        return self::$instance;
    }

    /**
     * Get message factory instance
     *
     * @access public
     * @return \JonnyW\PhantomJs\Message\MessageFactoryInterface
     */
    public function getMessageFactory()
    {
        return $this->messageFactory;
    }

    /**
     * Get procedure loader instance
     *
     * @access public
     * @return \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     */
    public function getProcedureLoader()
    {
        return $this->procedureLoader;
    }

    /**
     * Send request
     *
     * @access public
     * @param  \JonnyW\PhantomJs\Message\RequestInterface  $request
     * @param  \JonnyW\PhantomJs\Message\ResponseInterface $response
     * @return \JonnyW\PhantomJs\Message\ResponseInterface
     */
    public function send(RequestInterface $request, ResponseInterface $response)
    {
        $this->clearLog();

        $procedure = $this->procedureLoader->load($request->getType());
        $procedure->run($this, $request, $response);

        return $response;
    }

    /**
     * Get PhantomJs run command with
     * loader and run options.
     *
     * @access public
     * @return string
     */
    public function getCommand()
    {
        $phantomJs     = $this->getPhantomJs();
        $phantomLoader = $this->getPhantomLoader();

        $this->validateExecutable($phantomJs);
        $this->validateExecutable($phantomLoader);

        $this->addOption('--ssl-protocol=any');

        $options = $this->getOptions();

        if ($this->debug) {
            array_push($options, '--debug=true');
        }

        return sprintf('%s %s %s', $phantomJs, implode(' ', $options), $phantomLoader);
    }

    /**
     * Set bin directory.
     *
     * @access public
     * @param  string                   $path
     * @return \JonnyW\PhantomJs\Client
     */
    public function setBinDir($path)
    {
        $this->binDir = rtrim($path, '/\\');

        return $this;
    }

    /**
     * Get bin directory.
     *
     * @access public
     * @return string
     */
    public function getBinDir()
    {
        return $this->binDir;
    }

    /**
     * Set new PhantomJs executable path.
     *
     * @access public
     * @param  string                   $path
     * @return \JonnyW\PhantomJs\Client
     */
    public function setPhantomJs($path)
    {
        $this->validateExecutable($path);

        $this->phantomJs = $path;

        return $this;
    }

    /**
     * Get PhantomJs executable path.
     *
     * @access public
     * @return string
     */
    public function getPhantomJs()
    {
        return sprintf($this->phantomJs, $this->getBinDir());
    }

    /**
     * Set PhantomJs loader executable path.
     *
     * @access public
     * @param  string                   $path
     * @return \JonnyW\PhantomJs\Client
     */
    public function setPhantomLoader($path)
    {
        $this->validateExecutable($path);

        $this->phantomLoader = $path;

        return $this;
    }

    /**
     * Get PhantomJs loader executable path.
     *
     * @access public
     * @return string
     */
    public function getPhantomLoader()
    {
        return sprintf($this->phantomLoader, $this->getBinDir());
    }

    /**
     * Set PhantomJs run options.
     *
     * @access public
     * @param  array                    $options
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
     * @access public
     * @return array
     */
    public function getOptions()
    {
        return (array) $this->options;
    }

    /**
     * Add single PhantomJs run option.
     *
     * @access public
     * @param  string                   $option
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
     * @access public
     * @param  boolean                  $doDebug
     * @return \JonnyW\PhantomJs\Client
     */
    public function debug($doDebug)
    {
        $this->debug = $doDebug;

        return $this;
    }

    /**
     * Set log info.
     *
     * @access public
     * @param  string                   $info
     * @return \JonnyW\PhantomJs\Client
     */
    public function setLog($info)
    {
        $this->log = $info;

        return $this;
    }

    /**
     * Get log info.
     *
     * @access public
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Clear log info.
     *
     * @access public
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
     * @access private
     * @param  string                                                 $file
     * @return boolean
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

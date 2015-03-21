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
use JonnyW\PhantomJs\Procedure\ProcedureValidatorInterface;
use JonnyW\PhantomJs\Http\MessageFactoryInterface;
use JonnyW\PhantomJs\Http\RequestInterface;
use JonnyW\PhantomJs\Http\ResponseInterface;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Client implements ClientInterface
{
    /**
     * Client.
     *
     * @var \JonnyW\PhantomJs\ClientInterface
     * @access private
     */
    private static $instance;

    /**
     * Procedure loader.
     *
     * @var \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface
     * @access protected
     */
    protected $procedureLoader;

    /**
     * Procedure validator.
     *
     * @var \JonnyW\PhantomJs\Procedure\ProcedureValidatorInterface
     * @access protected
     */
    protected $procedureValidator;

    /**
     * Message factory.
     *
     * @var \JonnyW\PhantomJs\Http\MessageFactoryInterface
     * @access protected
     */
    protected $messageFactory;

    /**
     * Path to PhantomJs executable
     *
     * @var string
     * @access protected
     */
    protected $phantomJs;

    /**
     * Debug flag.
     *
     * @var boolean
     * @access protected
     */
    protected $debug;

    /**
     * PhantomJs run options.
     *
     * @var array
     * @access protected
     */
    protected $options;

    /**
     * Log info
     *
     * @var string
     * @access protected
     */
    protected $log;

    /**
     * Internal constructor
     *
     * @access public
     * @param  \JonnyW\PhantomJs\Procedure\ProcedureLoaderInterface    $procedureLoader
     * @param  \JonnyW\PhantomJs\Procedure\ProcedureValidatorInterface $procedureValidator
     * @param  \JonnyW\PhantomJs\Http\MessageFactoryInterface          $messageFactory
     * @return void
     */
    public function __construct(ProcedureLoaderInterface $procedureLoader, ProcedureValidatorInterface $procedureValidator, MessageFactoryInterface $messageFactory)
    {
        $this->procedureLoader    = $procedureLoader;
        $this->procedureValidator = $procedureValidator;
        $this->messageFactory     = $messageFactory;
        $this->phantomJs          = 'bin/phantomjs';
        $this->options            = array();
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

            self::$instance = new static(
                $serviceContainer->get('procedure_loader'),
                $serviceContainer->get('procedure_validator'),
                $serviceContainer->get('message_factory')
            );
        }

        return self::$instance;
    }

    /**
     * Get message factory instance
     *
     * @access public
     * @return \JonnyW\PhantomJs\Http\MessageFactoryInterface
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
     * @param  \JonnyW\PhantomJs\Http\RequestInterface  $request
     * @param  \JonnyW\PhantomJs\Http\ResponseInterface $response
     * @return \JonnyW\PhantomJs\Http\ResponseInterface
     */
    public function send(RequestInterface $request, ResponseInterface $response)
    {
        $procedure = $this->procedureLoader->load($request->getType());

        $this->procedureValidator->validate(
            $this,
            $procedure,
            $request
        );

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
        $phantomJs = $this->getPhantomJs();
        $options   = $this->getOptions();

        $this->validateExecutable($phantomJs);

        if ($this->debug) {
            array_push($options, '--debug=true');
        }

        return sprintf('%s %s', $phantomJs, implode(' ', $options));
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
        return $this->phantomJs;
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
     * Log info.
     *
     * @access public
     * @param  string                   $info
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

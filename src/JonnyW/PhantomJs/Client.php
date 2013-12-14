<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs;

use JonnyW\PhantomJs\ClientInterface;
use JonnyW\PhantomJs\Exception\NoPhantomJsException;
use JonnyW\PhantomJs\Exception\CommandFailedException;
use JonnyW\PhantomJs\Exception\NotWriteableException;
use JonnyW\PhantomJs\Message\FactoryInterface;
use JonnyW\PhantomJs\Message\Factory;
use JonnyW\PhantomJs\Message\RequestInterface;
use JonnyW\PhantomJs\Message\ResponseInterface;

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
	 * @var JonnyW\PhantomJs\ClientInterface
	 */
	private static $instance;

	/**
	 * Message factory instance
	 *
	 * @var JonnyW\PhantomJs\Message\FactoryInterface
	 */
	protected $factory;

	/**
	 * Path to phantomJS executable
	 *
	 * @var string
	 */
	protected $phantomJS;

	/**
	 * Internal constructor
	 *
	 * @param JonnyW\PhantomJs\Message\FactoryInterface $factory
	 * @return void
	 */
	public function __construct(FactoryInterface $factory = null)
	{
		if(!$factory instanceof FactoryInterface) {
			$factory = Factory::getInstance();
		}

		$this->factory  = $factory;
		$this->phantomJS  = app_path() . '\bin\phantomjs.exe';
		$this->timeout   = 5000;
	}

	/**
	 * Get singleton instance
	 *
	 * @param JonnyW\PhantomJs\Message\FactoryInterface $factory
	 * @return JonnyW\PhantomJs\ClientInterface
	 */
	public static function getInstance(FactoryInterface $factory = null)
	{
		if(!self::$instance instanceof ClientInterface) {
			self::$instance = new Client($factory);
		}

		return self::$instance;
	}

	/**
	 * Get message factory instance
	 *
	 * @return JonnyW\PhantomJs\Message\FactoryInterface
	 */
	public function getMessageFactory()
	{
		return $this->factory;
	}

	/**
	 * Send request
	 *
	 * @param JonnyW\PhantomJs\Message\RequestInterface $request
	 * @param JonnyW\PhantomJs\Message\ResponseInterface $response
	 * @param string $file
	 * @return JonnyW\PhantomJs\Message\ResponseInterface
	 */
	public function send(RequestInterface $request, ResponseInterface $response, $file = null)
	{
		if(!is_null($file)) {
			return $this->capture($request, $response, $file);
		}

		return $this->open($request, $response);
	}

	/**
	 * Open page
	 *
	 * @param JonnyW\PhantomJs\Message\RequestInterface $request
	 * @param JonnyW\PhantomJs\Message\ResponseInterface $response
	 * @return JonnyW\PhantomJs\Message\ResponseInterface
	 */
	public function open(RequestInterface $request, ResponseInterface $response)
	{
        return $this->request($request, $response, $this->openCmd);
	}

    /**
     * execute
     *
     * @param JonnyW\PhantomJs\Message\RequestInterface $request
     * @param JonnyW\PhantomJs\Message\ResponseInterface $response
     * @return JonnyW\PhantomJs\Message\ResponseInterface
     */
    public function execute(RequestInterface $request, ResponseInterface $response)
    {
        $args = func_get_args();
        array_splice($args, 2, 0, $this->openCmd);

        return call_user_func_array(array($this, "request"), $args);
    }

	/**
	 * Screen capture
	 *
	 * @param JonnyW\PhantomJs\Message\RequestInterface $request
	 * @param JonnyW\PhantomJs\Message\ResponseInterface $response
	 * @param string $file
	 * @return JonnyW\PhantomJs\Message\ResponseInterface
	 */
	public function capture(RequestInterface $request, ResponseInterface $response, $file)
	{
		if(!is_writable(dirname($file))) {
			throw new NotWriteableException(sprintf('Path is not writeable by PhantomJs: %s', $file));
		}

		$cmd = sprintf($this->captureCmd, $file);

		return $this->request($request, $response, $cmd);
	}

	/**
	 * Set new PhantomJs path
	 *
	 * @param string $path
	 * @return JonnyW\PhantomJs\ClientInterface
	 */
	public function setPhantomJs($path)
	{
		if(!file_exists($path) || !is_executable($path)) {
			throw new NoPhantomJsException(sprintf('PhantomJs file does not exist or is not executable: %s', $path));
		}

		$this->phantomJS = $path;

		return $this;
	}

	/**
	 * Set timeout period (in milliseconds)
	 *
	 * @param int $period
	 * @return JonnyW\PhantomJs\ClientInterface
	 */
	public function setTimeout($period)
	{
		$this->timeout = $period;

		return $this;
	}

	/**
	 * Make PhantomJS request
	 *
	 * @param JonnyW\PhantomJs\Message\RequestInterface $request
	 * @param JonnyW\PhantomJs\Message\ResponseInterface $response
	 * @param string $cmd
	 * @return JonnyW\PhantomJs\Message\ResponseInterface
	 */
	protected function request(RequestInterface $request, ResponseInterface $response, $cmd)
	{
		// Validate PhantomJS executable
		if(!file_exists($this->phantomJS) || !is_executable($this->phantomJS)) {
			throw new NoPhantomJsException(sprintf('PhantomJs file does not exist or is not executable: %s', $this->phantomJS));
		}

		try {

            if($request->getScript() == null){
                $script = false;

                $data = sprintf(
                    $this->wrapper,
                    $request->getHeaders('json'),
                    $this->timeout,
                    $request->getUrl(),
                    $request->getMethod(),
                    $request->getBody(),
                    $cmd
                );

                $script = $this->writeScript($data);
            }

            else{
                $script = $this->writeScript($request->getScript());
            }

            //Get the parameters passed into this function.
            $args = func_get_args();
            //We're unsetting the first 3 arguments, because we don't need them, and they'll mess up the rest of what we want to do.
            //Technically we don't need to as we're overwriting them later, but just to be super safe.
            unset($args[0], $args[1], $args[2]);

            //Replace some args with PhantomJs and the Script we're firing
            $args[1] = $this->phantomJS;
            $args[2] = $script;

            // building the format of the cmd string we'll end up producing
            $cmdString = "";
            foreach($args as $v){
                $cmdString .= "%s ";
            }
            //get rid of that trailing space.
            rtrim($cmdString, " ");
            $args[0] = $cmdString;

            //Sort the args by key so they aren't all messed up and screw the script up.
            ksort($args);

            // Just passing in the unknown number of args to the sprintf function
            $cmd  = escapeshellcmd(call_user_func_array("sprintf", $args));

			$result = shell_exec($cmd);

			$result = $this->parse($result);

			$this->removeScript($script);

			$response->setData($result);
		}
		catch(NotWriteableException $e) {
			throw $e;
		}
		catch(\Exception $e) {

			$this->removeScript($script);

			throw new CommandFailedException(sprintf('Error when executing PhantomJs command: %s - %s', $cmd, $e->getMessage()));
		}

		return $response;
	}

	/**
	 * Write temporary script file and
	 * return path to file
	 *
	 * @param string $data
	 * @return JonnyW\PhantomJs\ClientInterface
	 */
	protected function writeScript($data)
	{
		$file = tempnam('/tmp', 'phantomjs');

		// Could not create tmp file
		if(!$file || !is_writable($file)) {
			throw new NotWriteableException('Could not create tmp file on system. Please check your tmp directory and make sure it is writeable.');
		}

		// Could not write script data to tmp file
		if(file_put_contents($file, $data) === false) {

			$this->removeScript($file);

			throw new NotWriteableException(sprintf('Could not write data to tmp file: %s. Please check your tmp directory and make sure it is writeable.', $file));
		}

		return $file;
	}

	/**
	 * Remove temporary script file
	 *
	 * @param string $file
	 * @return JonnyW\PhantomJs\ClientInterface
	 */
	protected function removeScript($file)
	{
		if($file && file_exists($file)) {
			unlink($file);
		}

		return $this;
	}

	/**
	 * If data from JSON string format
	 * and return array
	 *
	 * @param string $data
	 * @return array
	 */
	protected function parse($data)
	{
		// Data is invalid
		if($data === null || !is_string($data)) {
			return array();
		}

        if(is_string($data)){
            return array("stringResponse" => $data);
        }

		// Not a JSON string
		if(substr($data, 0, 1) !== '{') {
			return array();
		}

		// Return decoded JSON string
		return (array) json_decode($data, true);
	}

	/**
	 * PhantomJs base wrapper
	 *
	 * @var string
	 */
	protected $wrapper = <<<EOF

	var page = require('webpage').create(),
		response = {},
		headers = %1\$s;

	page.settings.resourceTimeout = %2\$s;
	page.onResourceTimeout = function(e) {
		response 		= e;
		response.status = e.errorCode;
	};

	page.onResourceReceived = function (r) {
		if(!response.status) response = r;
	};

	page.customHeaders = headers ? headers : {};

	page.open('%3\$s', '%4\$s', '%5\$s', function(status) {

		if(status === 'success') {
			%6\$s
		}

		console.log(JSON.stringify(response, undefined, 4));
		phantom.exit();
	});
EOF;

	/**
	 * PhantomJs screen capture
	 * command template
	 *
	 * @var string
	 */
	protected $captureCmd = <<<EOF

			page.render('%1\$s');

			response.content = page.evaluate(function () {
				return document.getElementsByTagName('html')[0].innerHTML
			});
EOF;

	/**
	 * PhantomJs page open
	 * command template
	 *
	 * @var string
	 */
	protected $openCmd = <<<EOF

			response.content = page.evaluate(function () {
				return document.getElementsByTagName('html')[0].innerHTML
			});
EOF;
}
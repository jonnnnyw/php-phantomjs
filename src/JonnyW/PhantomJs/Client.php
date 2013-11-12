<?php

/*
 * This file is part of the php-phantomjs.
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs;

use  JonnyW\PhantomJs\ClientInterface;
use  JonnyW\PhantomJs\Exception\NoPhantomJsException;
use  JonnyW\PhantomJs\Exception\CommandFailedException;
use  JonnyW\PhantomJs\Exception\NotWriteableException;
use  JonnyW\PhantomJs\Exception\InvalidUrlException;
use  JonnyW\PhantomJs\Response;

/**
 * PHP PhantomJs
 *
 * @author Jon Wenmoth <contact@jonnyw.me>
 */
class Client implements ClientInterface
{
	/**
	 * Path to phantomJS executable
	 *
	 * @var string
	 */
	protected $phantomJS;
	
	/**
	 * Client instance
	 *
	 * @var JonnyW\PhantomJs\ClientInterface
	 */
	private static $instance;

	/**
	 * Internal constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->phantomJS 	= 'bin/phantomjs';
		$this->timeout 		= 5000;
	}
	
	/**
	 * Get singleton instance
	 *
	 * @return JonnyW\PhantomJs\ClientInterface
	 */
	public static function getInstance()
	{
		if(!self::$instance instanceof ClientInterface) {
			self::$instance = new Client();
		}

		return self::$instance;
	}
	
	/**
	 * Open page and return HTML
	 *
	 * @param string $url
	 * @return string
	 */
	public function open($url)
	{
		return $this->request($url, $this->openCmd);
	}
	
	/**
	 * Screen capture URL
	 *
	 * @param string $url
	 * @pram string $file
	 * @return string
	 */
	public function capture($url, $file)
	{
		if(!is_writable(dirname($file))) {
			throw new NotWriteableException(sprintf('Path is not writeable by PhantomJs: %s', $file));
		}
	
		$cmd = sprintf($this->captureCmd, $file);
	
		return $this->request($url, $cmd);
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
	 * Call PhantomJs command
	 *
	 * @param string $url
	 * @param string $cmd
	 * @return JonnyW\PhantomJs\Response
	 */
	protected function request($url, $cmd)
	{
		// Validate URL
		if(!filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) {
			throw new InvalidUrlException(sprintf('Invalid URL provided: %s', $url));
		}
	
		// Validate PhantomJS executable
		if(!file_exists($this->phantomJS) || !is_executable($this->phantomJS)) {
			throw new NoPhantomJsException(sprintf('PhantomJs file does not exist or is not executable: %s', $this->phantomJS));
		}
	
		try {
			
			$script = false;
			
			$data = sprintf(
				$this->wrapper,
				$this->timeout,
				$url, 
				$cmd
			);
	
			$script 	= $this->writeScript($data);
			$cmd 		= escapeshellcmd(sprintf("%s %s", $this->phantomJS, $script));
			
			$data = shell_exec($cmd);
			$data = $this->parse($data);
	
			$this->removeScript($script);
			
			$response = new Response($data);
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
		response = {};

	page.settings.resourceTimeout = %1\$s;		
	page.onResourceTimeout = function(e) {
		response 		= e;
		response.status = e.errorCode;
	};
	
	page.onResourceReceived = function (r) {
		if(!response.status) response = r;
	};
	
	page.open('%2\$s', function (status) {
	
		if(status === 'success') {
			%3\$s
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
<?php

/*
 * This file is part of the php-phantomjs.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace JonnyW\PhantomJs;

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
     * @var \JonnyW\PhantomJs\ClientInterface
     */
    private static $instance;

    /**
     * Message factory instance
     *
     * @var \JonnyW\PhantomJs\Message\FactoryInterface
     */
    protected $factory;

    /**
     * Path to phantomJS executable
     *
     * @var string
     */
    protected $phantomJS;

    /**
     * Request timeout period
     *
     * @var int
     */
    protected $timeout;

    /**
     * Internal constructor
     *
     * @param  \JonnyW\PhantomJs\Message\FactoryInterface $factory
     * @return \JonnyW\PhantomJs\Client
     */
    public function __construct(FactoryInterface $factory = null)
    {
        if (!$factory instanceof FactoryInterface) {
            $factory = Factory::getInstance();
        }

        $this->factory   = $factory;
        $this->phantomJS = 'bin/phantomjs';
        $this->timeout   = 5000;
    }

    /**
     * Get singleton instance
     *
     * @param  \JonnyW\PhantomJs\Message\FactoryInterface $factory
     * @return \JonnyW\PhantomJs\Client
     */
    public static function getInstance(FactoryInterface $factory = null)
    {
        if (!self::$instance instanceof ClientInterface) {
            self::$instance = new Client($factory);
        }

        return self::$instance;
    }

    /**
     * Get message factory instance
     *
     * @return \JonnyW\PhantomJs\Message\FactoryInterface
     */
    public function getMessageFactory()
    {
        return $this->factory;
    }

    /**
     * Send request
     *
     * @param  \JonnyW\PhantomJs\Message\RequestInterface  $request
     * @param  \JonnyW\PhantomJs\Message\ResponseInterface $response
     * @param  string                                      $file
     * @return \JonnyW\PhantomJs\Message\ResponseInterface
     */
    public function send(RequestInterface $request, ResponseInterface $response, $file = null)
    {
        if (!is_null($file)) {
            return $this->capture($request, $response, $file);
        }

        return $this->open($request, $response);
    }

    /**
     * Open page
     *
     * @param  \JonnyW\PhantomJs\Message\RequestInterface $request
     * @param  \JonnyW\PhantomJs\Message\ResponseInterface $response
     * @param int $delay
     * @return \JonnyW\PhantomJs\Message\ResponseInterface
     */
    public function open(RequestInterface $request, ResponseInterface $response, $delay = 0)
    {
        if ($delay) {
            $cmd = sprintf($this->openCmdWithDelay, $delay);
        } else {
            $cmd = $this->openCmd;
        }

        return $this->request($request, $response, $cmd);
    }

    /**
     * Screen capture
     *
     * @param  \JonnyW\PhantomJs\Message\RequestInterface $request
     * @param  \JonnyW\PhantomJs\Message\ResponseInterface $response
     * @param  string $file
     * @throws Exception\NotWriteableException
     * @return \JonnyW\PhantomJs\Message\ResponseInterface
     */
    public function capture(RequestInterface $request, ResponseInterface $response, $file)
    {
        if (!is_writable(dirname($file))) {
            throw new NotWriteableException(sprintf('Path is not writeable by PhantomJs: %s', $file));
        }

        $cmd = sprintf($this->captureCmd, $file);

        return $this->request($request, $response, $cmd);
    }

    /**
     * Set new PhantomJs path
     *
     * @param  string $path
     * @throws Exception\NoPhantomJsException
     * @return \JonnyW\PhantomJs\Client
     */
    public function setPhantomJs($path)
    {
        if (!file_exists($path) || !is_executable($path)) {
            throw new NoPhantomJsException(sprintf('PhantomJs file does not exist or is not executable: %s', $path));
        }

        $this->phantomJS = $path;

        return $this;
    }

    /**
     * Set timeout period (in milliseconds)
     *
     * @param  int                      $period
     * @return \JonnyW\PhantomJs\Client
     */
    public function setTimeout($period)
    {
        $this->timeout = $period;

        return $this;
    }

    /**
     * Make PhantomJS request
     *
     * @param  \JonnyW\PhantomJs\Message\RequestInterface $request
     * @param  \JonnyW\PhantomJs\Message\ResponseInterface $response
     * @param  string $cmd
     * @throws Exception\NoPhantomJsException
     * @throws \Exception
     * @throws Exception\NotWriteableException
     * @throws Exception\CommandFailedException
     * @return \JonnyW\PhantomJs\Message\ResponseInterface
     */
    protected function request(RequestInterface $request, ResponseInterface $response, $cmd)
    {

        // Validate PhantomJS executable
        if (!file_exists($this->phantomJS) || !is_executable($this->phantomJS)) {
            throw new NoPhantomJsException(sprintf('PhantomJs file does not exist or is not executable: %s', $this->phantomJS));
        }

        try {

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
            $cmd  = escapeshellcmd(sprintf("%s %s", $this->phantomJS, $script));

            $result = shell_exec($cmd);
            $result = $this->parse($result);

            $this->removeScript($script);

            $response->setData($result);
        } catch (NotWriteableException $e) {
            throw $e;
        } catch (\Exception $e) {

            $this->removeScript($script);

            throw new CommandFailedException(sprintf('Error when executing PhantomJs command: %s - %s', $cmd, $e->getMessage()));
        }

        return $response;
    }

    /**
     * Write temporary script file and
     * return path to file
     *
     * @param  string $data
     * @throws Exception\NotWriteableException
     * @return string
     */
    protected function writeScript($data)
    {
        $file = tempnam('/tmp', 'phantomjs');

        // Could not create tmp file
        if (!$file || !is_writable($file)) {
            throw new NotWriteableException('Could not create tmp file on system. Please check your tmp directory and make sure it is writeable.');
        }

        // Could not write script data to tmp file
        if (file_put_contents($file, $data) === false) {

            $this->removeScript($file);

            throw new NotWriteableException(sprintf('Could not write data to tmp file: %s. Please check your tmp directory and make sure it is writeable.', $file));
        }

        return $file;
    }

    /**
     * Remove temporary script file
     *
     * @param  string|boolean           $file
     * @return \JonnyW\PhantomJs\Client
     */
    protected function removeScript($file)
    {
        if (is_string($file) && file_exists($file)) {
            unlink($file);
        }

        return $this;
    }

    /**
     * If data from JSON string format
     * and return array
     *
     * @param  string $data
     * @return array
     */
    protected function parse($data)
    {
        // Data is invalid
        if ($data === null || !is_string($data)) {
            return array();
        }

        // Not a JSON string
        if (substr($data, 0, 1) !== '{') {
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
    page.onResourceTimeout = function (e) {
        response 		= e;
        response.status = e.errorCode;
    };

    page.onResourceReceived = function (r) {
        if(!response.status) response = r;
    };

    page.customHeaders = headers ? headers : {};

    page.open('%3\$s', '%4\$s', '%5\$s', function (status) {

        if (status === 'success') {
            %6\$s
        } else {
            console.log(JSON.stringify(response, undefined, 4));
            phantom.exit();
        }
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

            console.log(JSON.stringify(response, undefined, 4));
            phantom.exit();
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

            console.log(JSON.stringify(response, undefined, 4));
            phantom.exit();
EOF;

    /**
     * PhantomJs page open
     * command template with
     * delay
     *
     * @var string
     */
    protected $openCmdWithDelay = <<<EOF

        window.setTimeout(function () {

            response.content = page.evaluate(function () {
                return document.getElementsByTagName('html')[0].innerHTML
            });

            console.log(JSON.stringify(response, undefined, 4));
            phantom.exit();

        }, %s);
EOF;
}
